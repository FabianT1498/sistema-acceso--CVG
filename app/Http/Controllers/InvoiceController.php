<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\InvoiceDetail;
use App\Stock;
use App\State;
use App\Company;
use App\Item;
use App\Location;
use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class InvoiceController extends WebController
{
    const ENTREGRADA = 1;
    const ANULADA = 2;
	const DEVUELTA = 3;
    const POR_CONFIRMAR = 4;
    const CONFIRMADA = 5;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vista = $this::READ;
        $search = request('search');
        $start_date = request('start_date');
        $finish_date = request('finish_date');
        $trashed = request('trashed');
        // return request('trashed');
        $registros = null;
        if (request('buscar')) {
            if($trashed == 1)
            {
                $registros = Invoice::onlyTrashed()->where('register_by', 'ILIKE' ,$search)
                ->where('provider_name', 'ILIKE' ,$search)
                ->where('description', 'ILIKE' ,"%$search%")
                ->where('control_number', 'ILIKE' ,"%$search%");
            }
            else
            {
                $registros = Invoice::where('register_by', 'ILIKE' ,$search)
                ->where('provider_name', 'ILIKE' ,$search)
                ->where('description', 'ILIKE' ,"%$search%")
                ->where('control_number', 'ILIKE' ,"%$search%");
            }
            

            if($start_date && $finish_date)
            {
                $registros = $registros->whereDate('invoice_date','>=', $start_date)
                            ->whereDate('invoice_date', '<=', $finish_date);
            }
        }
        
        return view('invoice.read', compact('vista', 'search', 'trashed', 'registros', 'start_date', 'finish_date'));
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
        $start_date = request('start_date');
        $finish_date = request('finish_date');
        $trashed = (request('trashed')) ? true : false;
        $providers = Company::where('condition', 'P')->get();
        $locations = Location::all();
        
        return view('invoice.create', compact('vista', 'search', 'trashed', 'providers', 'locations', 'start_date', 'finish_date'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $request->all();

        $validator = Validator::make($request->all(), [

        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }
        $invoice = new Invoice();
        $invoice->invoice_date = $request->invoice_date;
        $invoice->company_id = $request->provider_id;
        $invoice->state_id = $this::POR_CONFIRMAR;
        $invoice->location_id = $request->location_id;
        $invoice->description = $request->description;
	    $invoice->currency_value =  (float)str_replace(',','.',str_replace('.','', $request->currency_value));
        $invoice->provider_name = Company::find($request->provider_id)->name;
        $invoice->provider_dni = Company::find($request->provider_id)->dni;
        $invoice->user_id = Auth::user()->id;
        $invoice->register_by = Auth::user()->firstname . " " . Auth::user()->lastname;
        $invoice->control_number = $this->control_number(explode("-",$request->invoice_date)[1]);

        if(!$invoice->save())
        {
            $request->session()->flash('alert-danger', 'No se pudo guardar la factura.');
            return redirect()->back()->withInput();
        }

        for ($i=0; $i < sizeof($request->quantity) ; $i++) {
            $item = Item::find($request->item[$i]);

            $invoice_detail = new InvoiceDetail();
	        $invoice_detail->invoice_id = $invoice->id;
            $invoice_detail->invoice_quantity = $request->quantity[$i];
            $invoice_detail->item_id = $request->item[$i];
            $invoice_detail->unit_cost_local_money = (float)str_replace(',','.', str_replace('.','', $request->unit_cost_local_money[$i]));
            $invoice_detail->unit_cost_foreign_money = (float)str_replace(',','.', str_replace('.','', $request->unit_cost_foreign_money[$i]));
            if(!$invoice_detail->save())
            {

            }

            //$stock = new Stock();
            //$stock->invoice_detail_id = $invoice_detail->id;
            //$stock->quantity_available = $invoice_detail->quantity;
            //if(!$stock->save())
            //{

            //}
	        //$item->average_cost = InvoiceDetail::withTrashed()->where('item_id', $item->id)->avg('unit_cost_local_money');
            //$item->quantity += $stock->quantity_available;
            //$item->update();

        }



        $vista = $this::READ;
        $search = request('search');
        $start_date = request('start_date');
        $finish_date = request('finish_date');
        $trashed = (request('trashed')) ? true : false;
        $buscar = true;
        toastr()->success(__('Registro creado con éxito'));
        return redirect()->route('compras.index', compact('vista', 'search', 'trashed', 'buscar', 'start_date', 'finish_date'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $providers = Company::where('condition', 'P')->get();
        $locations = Location::all();
        $states = State::where('type', '=', 'INVOICE')->get();
        $registro = Invoice::withTrashed()->where('id',$id)->first();
        $invoice_details = InvoiceDetail::withTrashed()->where('invoice_id', $id)->orderBy('id');
        $vista = $this::EDIT;
        $search = request('search');
        $start_date = request('start_date');
        $finish_date = request('finish_date');
        $trashed = (request('trashed')) ? true : false;
        return view('invoice.edit', compact('vista', 'search', 'trashed', 'registro', 'locations', 'providers', 'states', 'invoice_details', 'start_date', 'finish_date'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $invoice = Invoice::withTrashed()->where('id',$id)->first();
        $search = request('search');
        $start_date = request('start_date');
        $finish_date = request('finish_date');
        $trashed = (request('trashed')) ? true : false;
        $validator = Validator::make($request->all(), [
        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $state_old = $invoice->state_id;
        if($state_old == $this::POR_CONFIRMAR)
        {
        $invoice->invoice_date = $request->invoice_date;
        $invoice->provider_name = $request->provider_name;
        $invoice->currency_value =  (float)str_replace(',','.',str_replace('.','', $request->currency_value));
        $invoice->provider_dni = $request->provider_dni;
        $invoice->location_id = $request->location_id;
        $invoice->state_id = $request->state_id;
        if(!$invoice->update())
        {
            $search = request('search');
            $start_date = request('start_date');
            $finish_date = request('finish_date');
            $trashed = (request('trashed')) ? true : false;
            $buscar = true;
            toastr()->error(__('Error al actualizar el registro'));
            return redirect()->route('compras.index', compact('vista', 'search', 'trashed', 'buscar', 'start_date', 'finish_date'));
        }

        $invoice_details = InvoiceDetail::withTrashed()->where('invoice_id', $id)->orderBy('id')->get();

        
            foreach ($invoice_details as $key => $invoice_detail) {
                $invoice_detail->invoice_quantity = $request->invoice_quantity[$key];
                $invoice_detail->unit_cost_foreign_money =  (float)str_replace(',','.',str_replace('.','', $request->unit_cost_foreign_money[$key]));
                $invoice_detail->unit_cost_local_money =  (float)str_replace(',','.',str_replace('.','', $request->unit_cost_local_money[$key]));
                $invoice_detail->update();
            }
        }

        $vista = $this::READ;
        $search = request('search');
        $start_date = request('start_date');
        $finish_date = request('finish_date');
        $trashed = (request('trashed')) ? true : false;
        $buscar = true;
        toastr()->success(__('Registro actualizado con éxito'));
        return redirect()->route('compras.index', compact('vista', 'search', 'trashed', 'buscar', 'start_date', 'finish_date'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = Invoice::withTrashed()->where('id', $id)->first();
        $invoice->delete();
        $vista = $this::READ;
        $search = request('search');
        $start_date = request('start_date');
        $finish_date = request('finish_date');
        $trashed = (request('trashed')) ? true : false;
        $buscar = true;
        toastr()->success(__('Registro eliminado con éxito'));
        return redirect()->route('compras.index', compact('vista', 'search', 'trashed', 'buscar', 'start_date', 'finish_date'));
    }

    public function items_invoice()
    {
        $items = Item::all();

        return $items;
    }

    public function receiving_invoice($id)
    {

    }

    public function invoice_confirmation(Request $request, $id)
    {
        $invoice = Invoice::withTrashed()->where('id', $id)->first();
        $invoice_details = InvoiceDetail::withTrashed()->where('invoice_id', $invoice->id)->orderBy('asc')->get();

        foreach ($invoice_details as $key => $invoice_detail ) {
            $invoice_detail->recived_quantity = $request->recived_quantity[$key];
            $invoice_detail->update();

            $stock = new Stock();
            $stock->quantity_available = $invoice_detail->recived_quantity;
            $stock->invoice_detail_id = $invoice_detail->id;
            $stock->save();
        }

        $invoice->state_id = $this::CONFIRMADA;
        $invoice->update();

        //return a la vista

    }

    public function control_number($month)
    {
        $month = (int)$month;
        $year = date("y");
        $month = ($month < 10) ? "0" . $month : $month;
        $deliveries =   (Invoice::whereMonth('invoice_date',$month)->count()) + 1;

        $control_number = "C$month$year" . str_pad('' . $deliveries, 3, "0", \STR_PAD_LEFT) ;
        return $control_number;
    }



}

