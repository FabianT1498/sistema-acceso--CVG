<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Inventory;
use App\InventoryDetail;
use App\InvoiceDetail;
use App\Company;
use App\Location;
use App\State;
use App\Stock;
use App\Item;
use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class WarehouseController extends WebController
{
    const ENTREGRADA = 1;
    const ANULADA = 2;
	const DEVUELTA = 3;
    const POR_CONFIRMAR = 4;
    const CONFIRMADA = 5;
    const PENDIENTE = 6;
    const REALIZADO = 7;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $anioActual = date('Y');
        $mesActual = date("m");
        $estadisticas = new \stdClass();
        $estadisticas->recepcionPaquetes = Invoice::where('state_id', $this::POR_CONFIRMAR)->count();
        $estadisticas->inventariosPendientes = Inventory::where('state_id', $this::PENDIENTE)->count();
        $estadisticas->inventariosAnio = Inventory::whereYear('start_date', $anioActual)
                            ->count();
        $estadisticas->inventariosMes = Inventory::whereYear('start_date', $anioActual)
                            ->whereMonth('start_date', $mesActual)
                            ->count();

        return view('warehouse', compact('estadisticas'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function package_reception()
    {
        $vista = $this::READ;
        $search = request('search');
        $registros = null;
        if (request('buscar')) {
            $registros = Invoice::where('control_number', 'ILIKE' ,"%$search%")->where('state_id', $this::POR_CONFIRMAR);
        }
        return view('warehouse.reception.package_reception', compact('vista', 'search', 'registros'));
    }


/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function confirm_reception($id)
    {
        $invoice = Invoice::find($id);
        $vista = $this::EDIT;
        $search = request('search');
        $registros = null;
        if (request('buscar')) {
            $registros = Invoice::where('control_number', 'ILIKE' ,"%$search%")->where('state_id', $this::POR_CONFIRMAR);
        }
        $invoice_details = InvoiceDetail::where('invoice_id', $invoice->id)->orderBy('id');
        return view('warehouse.reception.confirm_reception', compact('vista', 'search', 'registros', 'invoice', 'invoice_details'));
    }



    public function confirming_reception(Request $request, $id)
    {
        $invoice = Invoice::find($id);
        $invoice->state_id = $this::CONFIRMADA;
        $invoice->confirmed_by = Auth::user()->firstname . " " . Auth::user()->lastname;
        $invoice->note = $request->note;
        $invoice->update();

        $invoice_details =  InvoiceDetail::where('invoice_id' , $invoice->id)->orderBy('id')->get();

        foreach ($invoice_details as $key => $invoice_detail) {
            $invoice_detail->recived_quantity = $request->recived_quantity[$key];

            $invoice_detail->update();

            $stock = new Stock();
            $stock->quantity_available = $invoice_detail->recived_quantity;
            $stock->invoice_detail_id = $invoice_detail->id;

            $stock->save();

        }

        $vista = $this::READ;
        $search = request('search');
        $registros = null;
        if (request('buscar')) {
            $registros = Invoice::where('control_number', 'ILIKE' ,"%$search%")->where('state_id', $this::POR_CONFIRMAR);
        }

        toastr()->success(__('Registro creado con éxito'));
        return redirect()->route('recepcion-paquetes', compact('vista', 'search', 'registros'));


    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pending_inventory()
    {
        $vista = $this::READ;
        $search = request('search');
        $registros = null;
        if (request('buscar')) {
            $registros = Inventory::where('start_date', 'ILIKE' ,"%$search%")->where('state_id', $this::PENDIENTE);
        }
        return view('warehouse.inventory.pending_inventory', compact('vista', 'search', 'registros'));
    }

    public function confirm_inventory($id)
    {
        $inventory = Inventory::find($id);
        $vista = $this::EDIT;
        $search = request('search');
        $registros = null;
        if (request('buscar')) {
            $registros = Inventory::where('control_number', 'ILIKE' ,"%$search%")->where('state_id', $this::PENDIENTE);
        }
        $inventory_details = InventoryDetail::where('inventory_id', $inventory->id)->orderBy('id');
        return view('warehouse.inventory.confirm_inventory', compact('vista', 'search', 'registros', 'inventory', 'inventory_details'));
    }

    public function confirming_inventory(Request $request, $id)
    {
        $inventory = Inventory::find($id);
        $inventory->state_id = $this::REALIZADO;
        $inventory->confirmed_by = Auth::user()->firstname . " " . Auth::user()->lastname;

        $inventory->update();

        $inventory_details =  InventoryDetail::where('inventory_id' , $inventory->id)->orderBy('id')->get();


        foreach ($inventory_details as $key => $inventory_detail) {
            if ($request->recived_quantity[$key] < $inventory_detail->quantity_stock)
            {
                //descontar del que tiene menos cantidad disponible
            }
            else if($request->recived_quantity[$key] < $inventory_detail->quantity_stock)
            {
                //aumentar del que tiene mas
            }
            $stocks = DB::table('stocks')
                ->select('stocks.id', 'stocks.quantity_available', 'stocks.invoice_detail_id')
                ->join('invoice_details', 'invoice_details.id', '=', 'stocks.invoice_detail_id')
                ->join('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
                ->where("invoice_details.item_id", $inventory_detail->item_id)
                ->where("invoices.location_id", $inventory->location_id)
                ->get();
        }

            //AYUDA
        for ($i=0; $i < sizeof($request->quantity) ; $i++) {
            $item = Item::find($request->item[$i]);
            if((int)$request->quantity[$i] <= $this::item_quantity($item->id, $inventory->location_id))
            {
                $prom = 1;
                $local_cost = 0;
                $foreign_cost = 0;
                $quantity = $request->quantity[$i];
                $stocks = DB::table('stocks')
                ->select('stocks.id', 'stocks.quantity_available', 'stocks.invoice_detail_id')
                ->join('invoice_details', 'invoice_details.id', '=', 'stocks.invoice_detail_id')
                ->join('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
                ->where("invoice_details.item_id", $item->id)
                ->where("invoices.location_id", $inventory->location_id)
                ->get();

                foreach($stocks as $my_stock)
                {
                    $stock = Stock::find($my_stock->id);
                    if($quantity <= $stock->quantity_available)
                    {

                        $local_cost += ($stock->unit_cost_local_money);
                        $local_cost /= $prom;
                        $prom = 1;
                        $foreign_cost += ($stock->unit_cost_foreign_money);
                        $foreign_cost /= $prom;
                        $totally_local_cost += $local_cost;

                        $totally_foreign_cost += $foreign_cost;

                        $stock->quantity_available -= $quantity;
                        if($stock->quantity_available == 0)
                        {
                            $stock->delete();
                        }
                        $stock->update();
                        break;
                    }
                    else {

                            $local_cost += $stock->unit_cost_local_money;
                            $foreign_cost += $stock->unit_cost_foreign_money;
                            $prom++;

                           $quantity -= $stock->quantity_available;
                           $stock->delete();

                    }
                }
                // $delivery_detail = new DeliveryDetail();
                // $delivery_detail->delivery_id = $delivery->id;
                // $delivery_detail->quantity = $request->quantity[$i];
                // $delivery_detail->item_id = $request->item[$i];
                // $delivery_detail->unit_cost_local_money = $local_cost;
                // $delivery_detail->unit_cost_foreign_money = $foreign_cost;

                // $delivery_detail->save();

            }
            else
            {
                // $delivery->delete();
                //No se pudo agregar la factura porque no hay la cantidad suficiente para satisfacer
                //uno de los productos
                break;
            }


        }

        $vista = $this::READ;
        $search = request('search');
        $registros = null;
        if (request('buscar')) {
            $registros = Inventory::where('control_number', 'ILIKE' ,"%$search%")->where('state_id', $this::PENDIENTE);
        }

        toastr()->success(__('Registro creado con éxito'));
        return redirect()->route('compras-almacen', compact('vista', 'search', 'registros'));


    }

    public function item_quantity($id, $location_id)
    {
        $items = DB::table('items')
                    ->join('invoice_details', 'items.id', '=', 'invoice_details.item_id')
                    ->join('stocks', 'invoice_details.id', '=', 'stocks.invoice_detail_id')
                    ->join('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
                    ->select(DB::raw("SUM(quantity_available) as quantity_available"))
                    ->where('invoices.location_id', '=', $location_id)
                    ->where('items.id', '=', $id)->get();

        return $items[0]->quantity_available;
    }


    public function confirmed_invoices()
    {
        $vista = $this::READ;
        $search = request('search');
        $start_date = request('start_date');
        $finish_date = request('finish_date');

        $registros = null;
        if (request('buscar')) {
            $registros = Invoice::where('register_by', 'ILIKE' ,$search)
                        ->where('provider_name', 'ILIKE' ,$search)
                        ->where('description', 'ILIKE' ,"%$search%")
                        ->where('state_id', '!=', 4)
                        ->where('control_number', 'ILIKE' ,"%$search%");

            if($start_date && $finish_date)
            {
                $registros = $registros->whereDate('invoice_date','>=', $start_date)
                            ->whereDate('invoice_date', '<=', $finish_date);
            }
        }

        return view('warehouse.confirmed_invoice.read', compact('vista', 'search', 'registros', 'start_date', 'finish_date'));
    
    }

    public function show_confirmed_invoices($id)
    {
        $providers = Company::where('condition', 'P')->get();
        $locations = Location::all();
        $states = State::where('type', '=', 'INVOICE')->get();
        $registro = Invoice::find($id);
        $invoice_details = InvoiceDetail::where('invoice_id', $id)->orderBy('id');
        $vista = $this::EDIT;
        $search = request('search');
        $start_date = request('start_date');
        $finish_date = request('finish_date');
        return view('warehouse.confirmed_invoice.edit', compact('vista', 'search', 'registro', 'locations', 'providers', 'states', 'invoice_details', 'start_date', 'finish_date'));
    }


}
