<?php

namespace App\Http\Controllers;

use App\Http\Controllers\WebController;
use App\Inventory;
use App\InventoryDetail;
use App\State;
use App\Item;
use App\Stock;
use App\Location;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDF;

class InventoryController extends WebController
{
    const PENDIENTE = '6';
    const REALIZADO = '7';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        $registros = null;
        if (request('buscar')) {
            if($trashed == 1)
            {
                $registros = Inventory::onlyTrashed()->where( function($query) use ($search){
                    $query->orWhere('description', 'ILIKE' ,"%$search%");
                    $query->orWhere('start_date', 'ILIKE' ,"%$search%");
                });
            }
            else
            {
                $registros = Inventory::where( function($query) use ($search){
                    $query->orWhere('description', 'ILIKE' ,"%$search%");
                    $query->orWhere('start_date', 'ILIKE' ,"%$search%");
                });
            }
            
        }
        return view('inventory.read', compact('vista', 'search', 'trashed', 'registros'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vista = $this::CREATE;
        $search = request('search');
        $trashed = request('trashed');
        $locations = Location::all();
        return view('inventory.create', compact('vista', 'search', 'trashed', 'locations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->has('btn-imprimir'));
        $inventory = new Inventory();
        $inventory->start_date = Carbon::now();
        $inventory->state_id = $this::PENDIENTE;
        $inventory->description = $request->description;
        $inventory->location_id = $request->location_id;

        DB::transaction(function() use ($request, $inventory) {
            $inventory->save();
            foreach ($request->check as $check) {
                $stocks = $this->stocks_item((int)$check, $request->location_id);
                foreach ($stocks as $stock){
                    $inventory_detail = new InventoryDetail();
                    $inventory_detail->inventory_id = $inventory->id;
                    $inventory_detail->quantity_stock = $stock->quantity_available;
                    $inventory_detail->invoice_detail_id = $stock->invoice_detail_id;
                    $inventory_detail->item_id = (int)$check;
                    $inventory_detail->save();
                }

            }
        });

        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        $buscar = true;
        toastr()->success(__('Registro creado con éxito'));
        return redirect()->route('inventarios.index', compact('vista', 'search', 'trashed', 'buscar'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $inventory = Inventory::withTrashed()->where('id', $id)->first();
        $vista = $this::EDIT;
        $search = request('search');
        $trashed = request('trashed');
        $buscar = false;
        $registro = $inventory;
        $details = $inventory->inventory_details()->withTrashed()->withTrashed()->orderBy('id')->get();
        $items = $inventory->inventory_details()->withTrashed()->with('item')->get()->pluck('item')->unique();
        return view('inventory.edit', compact('vista', 'search', 'trashed', 'buscar', 'registro', 'details', 'items'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $inventory = Inventory::withTrashed()->where('id', $id)->first();
        
        DB::transaction(function() use ($request, $inventory) {
            foreach ($inventory->inventory_details as  $inventory_detail) {
                $inventory_detail->quantity_inventory = ($request->{"quantity_stock_$inventory_detail->id"}) ? $request->{"quantity_stock_$inventory_detail->id"} : 0;
                $inventory_detail->note = $request->{"note_$inventory_detail->id"};
                $inventory_detail->save();
            }
            if ($request->has('finish_invenroty')) {
                $inventory->state_id = $this::REALIZADO;
                $inventory->finish_date = Carbon::now();
                $this->actualizarStock($request, $inventory);
            }
            $inventory->description = $request->description;
            $inventory->save();
        });
        $vista = $this::READ;
        $search = $request->description;
        $buscar = true;
        $trashed = request('trashed');
        toastr()->success(__('Registro actualizado con éxito'));
        return redirect()->route('inventarios.index', compact('vista', 'search', 'trashed', 'buscar'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateRegister(Request $request)
    {
        $detail = InventoryDetail::withTrashed()->where('id',$request->id)->first();
        $detail->quantity_inventory = ($request->quantity_inventory) ? $request->quantity_inventory : 0;
        $detail->note = $request->note;
        $detail->save();
        return $this->successResponse($detail);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $inventory = Inventory::withTrashed()->where('id', $id)->first();
        $inventory_details = InventoryDetail::withTrashed()->where('inventory_id', $inventory->id)->get();

        foreach ($inventory_details as $inventory_detail) {
            
            if($inventory->state_id == $this::REALIZADO)
            {
                $stock = Stock::withTrashed()->where('invoice_detail_id', $inventory_detail->invoice_detail_id)->first();

                if($stock->trashed())
                {
                    $stock->restore();
                }
                
                $stock->quantity_available = (int) $inventory_detail->quantity_stock;

                $stock->update();

            }
            
        $inventory_detail->delete();


        }

        $inventory->delete();
        $search = request('search');
        $trashed = request('trashed');
        $buscar = true;
        toastr()->success(__('Registro eliminado con éxito'));
        return redirect()->route('inventarios.index', compact('search', 'buscar'));
    }

    public function destroyRegister($id){
        $inventory = Inventory::withTrashed()->where('id', $id)->first();
        $detail = InventoryDetail::withTrashed()->where('inventory_id', $inventory->id)
                                 ->where('item_id', request()->item_id)->delete();
        $search = request()->search;
        $buscar = false;
        if (request()->count_items==1) {
            $inventory->delete();
            toastr()->success(__('El Inventario ha sido eliminado con éxito'));
            return redirect()->route('inventarios.index', compact('search', 'buscar'));
        }else{
            toastr()->success(__('El Item ha sido eliminado con éxito'));
            return redirect()->route('inventarios.edit', $inventory->id)->with(compact('search'));
        }

    }

    public function items_inventory(Request $request)
    {
        $items_builder = DB::table('items')
                    ->join('invoice_details', 'items.id', '=', 'invoice_details.item_id')
                    ->join('stocks', 'invoice_details.id', '=', 'stocks.invoice_detail_id')
                    ->join('presentations', 'presentations.id', '=', 'items.presentation_id')
                    ->join('types', 'types.id', '=', 'items.type_id')
                    ->join('sub_groups', 'sub_groups.id', '=', 'types.sub_group_id')
                    ->join('groups', 'groups.id', '=', 'sub_groups.group_id')
                    ->join('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
                    ->where('stocks.quantity_available', '>', '0')
                    ->where('invoices.location_id', '=', $request->location_id)
                    ->select('items.id', 'items.description', 'types.name as type', 'presentations.name as presentation', 'sub_groups.name as sub_group', 'groups.name as group')
                   ->groupBy('items.id', 'types.name', 'presentations.name', 'sub_groups.name', 'groups.name')->get();
     
        return response()->json([
            'data' => $items_builder]);
    }

    private function stocks_item($id, $location_id){

        $stocks = DB::table('stocks')
                    ->join('invoice_details', 'stocks.invoice_detail_id', '=', 'invoice_details.id')
                    ->join('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
                    ->select('stocks.id',
                             'stocks.quantity_available',
                             'stocks.invoice_detail_id')
                    ->where('invoice_details.item_id', '=', $id)
                    ->where('stocks.quantity_available', '>', 0)
                    ->where('invoices.location_id', '=', $location_id)
                    ->get();
        return $stocks;
    }

    private function actualizarStock($request, $inventory){
        $items = $this->getItems($request, $inventory);
        foreach($items as $item){
            $stocks = $this->stocks_item($item->id, $inventory->location_id);
            foreach($stocks as $stock){
                foreach($inventory->inventory_details as  $inventory_detail){
                    if ($stock->invoice_detail_id===$inventory_detail->invoice_detail_id) {
                        $registroStock = Stock::findOrFail($stock->id);
                        $registroStock->quantity_available = $inventory_detail->quantity_inventory;
                        $registroStock->update();
                        if($registroStock->quantity_available == 0)
                            $registroStock->delete();
                    }
                }
            }
        }

        foreach ($inventory->inventory_details as  $inventory_detail) {

            $inventory_detail->quantity_inventory = $request->{"quantity_stock_$inventory_detail->id"};
            $inventory_detail->save();
        }
    }

    public function printRegister(Request $request, $id){
        $inventory = Inventory::findOrFail($id);
        $registro = $inventory;
        $details = $inventory->inventory_details()->withTrashed()->orderBy('id')->get();
        $items = $this->getItems($request, $inventory);
        $pdf = PDF::loadView('inventory.pdf_inventory', compact('registro', 'details', 'items'));
        //return view('inventory.pdf_inventory', compact('registro', 'details', 'items'));
        return $pdf->stream();
    }

    private function getItems($request, $inventory){
        $items = [];
        if ($request->has('chkRegistrosImprimir')) {
            $checksParaImprimir = json_decode($request->chkRegistrosImprimir);
            if (count($checksParaImprimir)) {
                $items_tmp = $inventory->inventory_details()->with('item')->get()->pluck('item')->unique();
                foreach($items_tmp as $item){
                    foreach($checksParaImprimir as $checkParaImprimir){
                        if ((int)$item->id == (int)$checkParaImprimir) {
                            array_push($items, $item);
                            break;
                        }
                    }
                }

            }else{
                $items = $inventory->inventory_details()->with('item')->get()->pluck('item')->unique();
            }
        }else{
            $items = $inventory->inventory_details()->with('item')->get()->pluck('item')->unique();
        }
        return $items;
    }


}
