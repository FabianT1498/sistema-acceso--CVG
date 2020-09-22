<?php

namespace App\Http\Controllers;

use App\Stock;
use App\Item;
use App\Location;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;

class StockController extends WebController
{
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
        $location_id = request('location_id');
        $locations = Location::all();

        $registros = null;
        if (request('buscar')) {
            if($location_id == 0)
            {
                $registros = DB::table('items')
                ->join('invoice_details', 'items.id', '=', 'invoice_details.item_id')
                ->join('stocks', 'invoice_details.id', '=', 'stocks.invoice_detail_id')
                // ->where('stocks.quantity_available', '>', 0)
                ->groupBy('items.id')
                ->select('items.description', 'items.id',DB::raw('SUM(invoice_details.unit_cost_foreign_money) as sum'),DB::raw('SUM(stocks.quantity_available) as quantity_available'), DB::raw('AVG(invoice_details.unit_cost_foreign_money) as avg'))
                ->get();
            }
            
            else
            {
                $registros = DB::table('items')
                    ->join('invoice_details', 'items.id', '=', 'invoice_details.item_id')
                    ->join('stocks', 'invoice_details.id', '=', 'stocks.invoice_detail_id')
                    ->join('invoices', 'invoice_details.invoice_id', '=', 'invoices.id')
                    ->where('invoices.location_id', '=', $location_id)
                    // ->where('stocks.quantity_available', '>', 0)
                    ->groupBy('items.id')
                    ->select('items.description', 'items.id',DB::raw('SUM(invoice_details.unit_cost_foreign_money) as sum'),DB::raw('SUM(stocks.quantity_available) as quantity_available'), DB::raw('AVG(invoice_details.unit_cost_foreign_money) as avg'))
                    ->get();
            }

            
        }
    
        return view('stock.read', compact('vista', 'search', 'registros', 'location_id', 'locations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show(Stock $stock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $registro = Item::find($id);
        $vista = $this::EDIT;
        $location_id = request('location_id');
        $locations = Location::all();
        $search = request('search');
        $buscar = false;
        $stocks = DB::table('stocks')
                    ->join('invoice_details', 'stocks.invoice_detail_id', '=', 'invoice_details.id')
                    ->join('items', 'invoice_details.item_id', '=', 'items.id')
                    ->join('invoices', 'invoice_details.invoice_id', '=', 'invoices.id')
                    ->where('stocks.quantity_available', '>', 0)
                    ->select( 'stocks.id','invoices.invoice_date', 'stocks.quantity_available', 'invoice_details.unit_cost_foreign_money')
                    ->get();
     
        return view('stock.edit', compact('vista', 'search', 'buscar', 'location_id', 'locations', 'stocks', 'registro'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        for($i = 0; $i < sizeof($request->stocks); $i++)
        {
            $stock = Stock::find($request->stocks[$i]);
            $stock->quantity_available = $request->quantity_available[$i];
            $stock->update();
        }

        $vista = $this::READ;
        $search = request('search');
        $buscar = true;
        $location_id = request('location_id');
        $locations = Location::all();
        toastr()->success(__('Registro actualizado con éxito'));
        return redirect()->route('stocks', compact('vista', 'search', 'buscar' ,'location_id', 'locations'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stock $stock)
    {
        //
    }


    public function stock_item(Request $request ,$id)
    {
        $registros = DB::table('stocks')
                    ->join('invoice_details', 'invoice_details.id', '=', 'stocks.invoice_detail_id')
                    ->join('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
                    ->where('invoice_details.item_id', '=', $id)
                    ->where('invoices.location_id', '=', $request->location_id)
                    ->select(DB::raw('SUM(stocks.quantity_available) as sum'))
                    ->get();

        if($registros){
            return response()->json([
                'status'=>'success',
                'code'=>201,
                'data'=> $registros[0]
            ],201);

        }else{
            return response()->json([
                'status'=>'error',
                'code'=>404,
                'message'=>''
            ],200);
        }

    }
}
