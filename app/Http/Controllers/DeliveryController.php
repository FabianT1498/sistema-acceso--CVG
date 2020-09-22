<?php

namespace App\Http\Controllers;
use PDF;
use App\Item;
use App\Group;
use App\User;
use App\Stock;
use App\StockDeliveryDetail;
use App\Company;
use App\Delivery;
use App\Location;
use Carbon\Carbon;
use App\DeliveryDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Validator;

class DeliveryController extends WebController
{
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
        $start_date = request('start_date');
        $finish_date = request('finish_date');
        $group_id = request('group_id');
        $company_id = request('company_id');
        $analyst_id = request('analyst_id');
        $groups = Group::all();
        $company_id = request('company_id');
        $companies = Company::where('condition', 'C')->get();
        $registros = null;
        if (request('buscar')) {

            // $registros = Delivery::orWhere('deliverer', 'ILIKE' ,"%$search%")
            //                         ->orWhere('unity', 'ILIKE', "%$search%")
            //                         ->orWhere('control_number', 'ILIKE', "%$search%")
            //                         ->orWhere('dni_deliverer', 'ILIKE', "%$search%")
            //                         ->orWhere('description', 'ILIKE', "%$search%");

            if($trashed == 1)
            {
                $registros = Delivery::onlyTrashed()->select('deliveries.*', 'companies.name')
                ->join('companies', 'companies.id', '=', 'deliveries.company_id')
                ->where(function($q) use ($search){
                    $q->orWhere('deliverer', 'ILIKE' ,"%$search%")
                    ->orWhere('unity', 'ILIKE', "%$search%")
                    ->orWhere('control_number', 'ILIKE', "%$search%")
                    ->orWhere('dni_deliverer', 'ILIKE', "%$search%")
                    ->orWhere('deliveries.description', 'ILIKE', "%$search%")
                    ->orWhere('companies.name', 'ILIKE', "%$search%");
                })
                ;

            }
            else
            {
                $registros = Delivery::select('deliveries.*', 'companies.name')
                ->join('companies', 'companies.id', '=', 'deliveries.company_id')
                ->where(function($q) use ($search){
                    $q->orWhere('deliverer', 'ILIKE' ,"%$search%")
                    ->orWhere('unity', 'ILIKE', "%$search%")
                    ->orWhere('control_number', 'ILIKE', "%$search%")
                    ->orWhere('dni_deliverer', 'ILIKE', "%$search%")
                    ->orWhere('deliveries.description', 'ILIKE', "%$search%")
                    ->orWhere('companies.name', 'ILIKE', "%$search%");
                })
                ;

            }

            if($start_date && $finish_date)
            {
                $registros = $registros->whereDate('delivered_date','>=', $start_date)
                            ->whereDate('delivered_date', '<=', $finish_date);
            }
            if($company_id != 0)
            {
                $registros = $registros->where('deliveries.company_id', '=', $company_id);
            }
            if($group_id != 0)
            {
                $registros = $registros->join('delivery_details', 'delivery_details.delivery_id', 'deliveries.id')
                ->join('items', 'items.id', 'delivery_details.item_id')
                ->join('types', 'types.id', 'items.type_id')
                ->join('sub_groups', 'sub_groups.id', 'types.sub_group_id')
                ->join('groups', 'groups.id', 'sub_groups.group_id')
                ->where('groups.id', '=', $group_id);
            }

            if((Auth::user()->role->name == "ADMIN" || Auth::user()->role->name == "SUPERADMIN"))
            {
                if($analyst_id != 0)
                {
                    $registros = $registros->where('deliveries.user_id', '=', $analyst_id);
                }
            }
            else
            {
                $registros = $registros->where('deliveries.user_id', '=', Auth::user()->id);
            }


        }

        if(Auth::user()->role->name == "ADMIN" || Auth::user()->role->name == "SUPERADMIN")
        {
            $analysts = User::select('users.firstname', 'users.lastname', 'users.dni', 'users.id')
                            ->join('roles', 'roles.id', 'users.role_id')
                            ->where( function ($q)
                            {
                                $q->orWhere('roles.name', '=', 'ADMIN')
                                ->orWhere('roles.name', '=', 'ANALISTA');
                            })->get();
            return view('delivery.read', compact('vista', 'search', 'trashed', 'start_date', 'finish_date', 'registros', 'groups', 'group_id', 'company_id', 'companies', 'analysts', 'analyst_id' ));

        }
        return view('delivery.read', compact('vista', 'search', 'trashed', 'start_date', 'finish_date', 'registros', 'groups', 'group_id', 'company_id', 'companies'));
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
        $start_date = request('start_date');
        $finish_date = request('finish_date');
        $group_id = request('group_id');
        $company_id = request('company_id');
        $analyst_id = request('analyst_id');
        $locations = Location::all();
        $groups = Group::all();
        $companies = Company::where('condition', 'C')->get();

        if(Auth::user()->role->name == 'ADMIN' || Auth::user()->role->name == 'SUPERADMIN')
        {
            $analysts = User::select('users.firstname', 'users.lastname', 'users.dni', 'users.id')
                            ->join('roles', 'roles.id', 'users.role_id')
                            ->where( function ($q)
                            {
                                $q->orWhere('roles.name', '=', 'ADMIN')
                                ->orWhere('roles.name', '=', 'ANALISTA');
                            })->get();

            return view('delivery.create', compact('vista', 'search', 'trashed', 'start_date', 'finish_date', 'companies', 'groups', 'group_id', 'company_id', 'locations', 'companies', 'analysts', 'analyst_id'));

        }

        return view('delivery.create', compact('vista', 'search', 'trashed', 'start_date', 'finish_date', 'companies', 'groups', 'group_id', 'company_id', 'locations', 'companies'));
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
        $unit_local_costs = [];
        $totally_local_cost = 0;
        $unit_foreign_costs = [];
        $totally_foreign_cost = 0;

        $validator = Validator::make($request->all(), [

        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $valores = array_count_values($request->item);
        foreach($valores as $valor)
        {
            if($valor > 1)
            {
                return redirect()->back()->withErrors(['Está seleccionando un ITEM dos veces, por favor solo seleccionelo una vez.']);
            }
        }

        //VALIDAMOS PRIMERO SI LA CANTIDAD DE ITEMS QUE SE VAN A ENTREGAR, HAY EN EXISTENCIA
        for ($i=0; $i < sizeof($request->quantity) ; $i++) 
        {
            $quantity = $request->quantity[$i];
            
            $item_in_stock = DB::table('stocks')
                            ->join('invoice_details', 'stocks.invoice_detail_id', '=', 'invoice_details.id')
                            ->join('items', 'invoice_details.item_id', '=', 'items.id')
                            ->join('invoices', 'invoice_details.invoice_id', '=', 'invoices.id')
                            ->where('stocks.quantity_available', '>', 0)
                            ->where('invoices.location_id', '=', $request->location_id)
                            ->where('items.id', '=', $request->item[$i])
                            ->groupBy('items.id')
                            ->select(DB::raw('SUM(stocks.quantity_available) as quantity_available'))
                            ->get();

            if($item_in_stock[0]->quantity_available < $quantity)
            {
                return redirect()->back()->withErrors(['No hay la suficiente cantidad para realizar este pedido.']);

            }
        }


        
        $delivery = new Delivery();
        $delivery->delivered_date = $request->delivered_date;
        $delivery->company_id = $request->company_id;
        $delivery->location_id = $request->location_id;
        $delivery->unity = $request->unity;
        $delivery->description = $request->description;
        $delivery->control_number = $this->control_number(explode("-",$request->delivered_date)[1]);
        $delivery->dni_deliverer = Auth::user()->dni;
        $delivery->user_id = Auth::user()->id;
        $delivery->deliverer = Auth::user()->firstname . " " . Auth::user()->lastname;

        if(!$delivery->save())
        {
            $request->session()->flash('alert-danger', 'No se pudo guardar la factura.');
            return redirect()->back()->withInput();
        }

        for ($i=0; $i < sizeof($request->quantity) ; $i++) {
            $item = Item::find($request->item[$i]);
            if((int)$request->quantity[$i] <= $this::item_quantity($item->id, $request->location_id))
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
                ->where("invoices.location_id", $request->location_id)
                ->get();
                $stock_delivery_array = array();
                foreach($stocks as $my_stock)
                {
                    $stock_delivery_detail = new StockDeliveryDetail();
                    $stock = Stock::find($my_stock->id);
                    $stock_delivery_detail->stock_id = $stock->id;
                    if($quantity <= $stock->quantity_available)
                    {

                        $local_cost += ($stock->invoice_detail->unit_cost_local_money);
                        $local_cost /= $prom;
                        $prom = 1;
                        $foreign_cost += ($stock->invoice_detail->unit_cost_foreign_money);
                        $foreign_cost /= $prom;
                        $totally_local_cost += $local_cost;

                        $totally_foreign_cost += $foreign_cost;

			            //array_push($unit_local_cost, $local_cost);

                        $stock->quantity_available -= $quantity;
                        $stock_delivery_detail->quantity_released = $quantity;
                        $stock->update();
                        if($stock->quantity_available == 0)
                        {
                            $stock->delete();
                        }
                        $stock_delivery_detail->save();
                        array_push($stock_delivery_array, $stock_delivery_detail);
                        break;
                    }
                    else {

                        $local_cost += $stock->invoice_detail->unit_cost_local_money;
                        $foreign_cost += $stock->invoice_detail->unit_cost_foreign_money;
                        $prom++;

                        $quantity -= $stock->quantity_available;
                        $stock_delivery_detail->quantity_released = $stock->quantity_available;
                        $stock->quantity_available = 0;
                        $stock->update();
                        $stock->delete();
                        $stock_delivery_detail->save();
                        array_push($stock_delivery_array, $stock_delivery_detail);

                    }
                }
                $delivery_detail = new DeliveryDetail();
                $delivery_detail->delivery_id = $delivery->id;
                $delivery_detail->quantity = $request->quantity[$i];
                $delivery_detail->item_id = $request->item[$i];
                $delivery_detail->unit_cost_local_money = $local_cost;
                $delivery_detail->unit_cost_foreign_money = $foreign_cost;
                $delivery_detail->save();

                foreach ($stock_delivery_array as $stock_delivery) {
                    $stock_delivery->delivery_detail_id = $delivery_detail->id;
                    $stock_delivery->update();

                }

            }
            else
            {
                $delivery->delete();
                return redirect()->back()->withErrors(['No hay la suficiente cantidad para realizar este pedido.']);

                //No se pudo agregar la factura porque no hay la cantidad suficiente para satisfacer
                //uno de los productos
                break;
            }


        }



        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        $start_date = request('start_date');
        $finish_date = request('finish_date');
        $group_id = request('group_id');
        $company_id = request('company_id');
        $analyst_id = request('analyst_id');
        $groups = Group::all();
        $companies = Company::where('condition', 'C')->get();
        $buscar = true;
        toastr()->success(__('Registro creado con éxito'));
        if(Auth::user()->role->name == 'ADMIN' || Auth::user()->role->name == 'SUPERADMIN')
        {
            $analysts = User::select('users.firstname', 'users.lastname', 'users.dni', 'users.id')
                            ->join('roles', 'roles.id', 'users.role_id')
                            ->where( function ($q)
                            {
                                $q->orWhere('roles.name', '=', 'ADMIN')
                                ->orWhere('roles.name', '=', 'ANALISTA');
                            })->get();
            
            return redirect()->route('entregas.index', compact('vista', 'search', 'trashed', 'start_date', 'finish_date', 'buscar', 'groups', 'group_id', 'company_id', 'companies', 'analysts', 'analyst_id'));

            
        }

        return redirect()->route('entregas.index', compact('vista', 'search', 'trashed', 'start_date', 'finish_date', 'buscar', 'group_id', 'company_id', 'companies', 'groups'));
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $registro = Delivery::withTrashed()->where('id',$id)->first();
        $delivery_details = DeliveryDetail::where('delivery_id', $id);
	    $companies = Company::where('condition', 'C')->get();
        $vista = $this::EDIT;
        $search = request('search');
        $trashed = request('trashed');
        $start_date = request('start_date');
        $finish_date = request('finish_date');
        $group_id = request('group_id');
        $company_id = request('company_id');
        $analyst_id = request('analyst_id');
        $locations = Location::all();
        $groups = Group::all();
        
        if(Auth::user()->role->name == 'ADMIN' || Auth::user()->role->name == 'SUPERADMIN')
        {
            $analysts = User::select('users.firstname', 'users.lastname', 'users.dni', 'users.id')
                            ->join('roles', 'roles.id', 'users.role_id')
                            ->where( function ($q)
                            {
                                $q->orWhere('roles.name', '=', 'ADMIN')
                                ->orWhere('roles.name', '=', 'ANALISTA');
                            })->get();

            return view('delivery.edit', compact('vista', 'search', 'trashed', 'start_date', 'finish_date', 'companies', 'registro', 'delivery_details', 'groups', 'group_id', 'company_id', 'companies', 'analysts', 'analyst_id', 'locations'));

        }
        return view('delivery.edit', compact('vista', 'search', 'trashed', 'start_date', 'finish_date', 'companies', 'registro', 'delivery_details', 'groups', 'group_id', 'company_id', 'companies', 'locations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $search = request('search');
        $trashed = request('trashed');
        $start_date = request('start_date');
        $finish_date = request('finish_date');
        $validator = Validator::make($request->all(), [
            
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)
                        ->withInput();
        }
        $delivery = Delivery::withTrashed()->where('id',$id)->first();
        $delivery->name = $request->name;
        $delivery->description = $request->description;
        $delivery->unity = $request->unity;
        if(!$delivery->update())
        {
            $search = request('search');
            $trashed = request('trashed');
            $start_date = request('start_date');
            $finish_date = request('finish_date');
            $buscar = true;
            toastr()->error(__('Error al actualizar el registro'));
            if(Auth::user()->role->name == 'ADMIN' || Auth::user()->role->name == 'SUPERADMIN')
        {
            $analysts = User::select('users.firstname', 'users.lastname', 'users.dni', 'users.id')
                            ->join('roles', 'roles.id', 'users.role_id')
                            ->where( function ($q)
                            {
                                $q->orWhere('roles.name', '=', 'ADMIN')
                                ->orWhere('roles.name', '=', 'ANALISTA');
                            })->get();

            return redirect()->route('entregas.index', compact('vista', 'search', 'trashed', 'start_date', 'finish_date', 'buscar', 'groups', 'group_id', 'company_id', 'companies', 'analysts', 'analyst_id'));

            
        }

            return redirect()->route('entregas.index', compact('vista', 'search', 'trashed', 'start_date', 'finish_date', 'buscar', 'groups', 'group_id', 'company_id', 'companies'));
        }

        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        $start_date = request('start_date');
        $finish_date = request('finish_date');
        $group_id = request('group_id');
        $company_id = request('company_id');
        $analyst_id = request('analyst_id');
        $companies = Company::where('condition', 'C')->get();
        $groups = Group::all();
        $buscar = true;
        toastr()->success(__('Registro actualizado con éxito'));
        if(Auth::user()->role->name == 'ADMIN' || Auth::user()->role->name == 'SUPERADMIN')
        {
            $analysts = User::select('users.firstname', 'users.lastname', 'users.dni', 'users.id')
                            ->join('roles', 'roles.id', 'users.role_id')
                            ->where( function ($q)
                            {
                                $q->orWhere('roles.name', '=', 'ADMIN')
                                ->orWhere('roles.name', '=', 'ANALISTA');
                            })->get();

        return redirect()->route('entregas.index', compact('vista', 'search', 'trashed', 'start_date', 'finish_date', 'buscar', 'groups', 'group_id', 'company_id', 'companies', 'analysts', 'analyst_id'));

        }

        return redirect()->route('entregas.index', compact('vista', 'search', 'trashed', 'start_date', 'finish_date', 'buscar', 'groups', 'group_id', 'company_id', 'companies'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delivery = Delivery::withTrashed()->where('id',$id)->first();


        $delivery_details = DeliveryDetail::where('delivery_id', $delivery->id)->get();

        foreach ($delivery_details as $delivery_detail) {
            $stock_delivery_details = StockDeliveryDetail::where('delivery_detail_id', $delivery_detail->id)->get();
            
            foreach ($stock_delivery_details as $stock_delivery_detail) {
                $stock = Stock::withTrashed()->where('id',$stock_delivery_detail->stock_id)->first();

                if($stock->trashed())
                {
                    $stock->restore();
                }

                $stock->quantity_available += $stock_delivery_detail->quantity_released;

                $stock->update();
            }

            
            $delivery_detail->delete();

        }



        $delivery->delete();

        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        $start_date = request('start_date');
        $finish_date = request('finish_date');
        $group_id = request('group_id');
        $company_id = request('company_id');
        $analyst_id = request('analyst_id');
        $companies = Company::where('condition', 'C')->get();
        $groups = Group::all();
        $buscar = true;
        toastr()->success(__('Registro eliminado con éxito'));
        if(Auth::user()->role->name == 'ADMIN' || Auth::user()->role->name == 'SUPERADMIN')
        {
            $analysts = User::select('users.firstname', 'users.lastname', 'users.dni', 'users.id')
                            ->join('roles', 'roles.id', 'users.role_id')
                            ->where( function ($q)
                            {
                                $q->orWhere('roles.name', '=', 'ADMIN')
                                ->orWhere('roles.name', '=', 'ANALISTA');
                            })->get();
                            return redirect()->route('entregas.index', compact('vista', 'search', 'trashed', 'start_date', 'finish_date', 'buscar', 'groups', 'group_id', 'company_id', 'companies', 'analysts', 'analyst_id'));

            
        }
        return redirect()->route('entregas.index', compact('vista', 'search', 'trashed', 'start_date', 'finish_date', 'buscar', 'groups', 'group_id', 'company_id', 'companies'));
    }

    public function items_delivery(Request $request)
    {
        $items = DB::table('items')
                    ->select('items.*')
                    ->join('invoice_details', 'items.id', '=', 'invoice_details.item_id')
                    ->join('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
                    ->join('stocks', 'invoice_details.id', '=', 'stocks.invoice_detail_id')
                    ->where('stocks.quantity_available', '>', '0')
                    ->whereNull('items.deleted_at')
                    ->where('invoices.location_id', '=', $request->location)
                   ->groupBy('items.id')->get();

        return $items;
    }

    public function control_number($month)
    {
        $month = (int)$month;
        $year = date("y");
        $month = ($month < 10) ? "0" . $month : $month;
        $deliveries =   (Delivery::whereMonth('delivered_date',$month)->count()) + 1;

        $control_number = "E$month$year" . str_pad('' . $deliveries, 3, "0", \STR_PAD_LEFT) ;
        return $control_number;
    }


    public function item_quantity($id, $location_id)
    {
        $items = DB::table('items')
                    ->join('invoice_details', 'items.id', '=', 'invoice_details.item_id')
                    ->join('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
                    ->join('stocks', 'invoice_details.id', '=', 'stocks.invoice_detail_id')
                    ->select(DB::raw("SUM(quantity_available) as quantity_available"))
                    ->where('invoices.location_id', '=', $location_id)
                    ->whereNull('items.deleted_at')
                    ->whereNull('invoice_details.deleted_at')
                    ->whereNull('invoices.deleted_at')
                    ->where('items.id', '=', $id)->get();

        return $items[0]->quantity_available;
    }

    public function printList(Request $request){
        $start_date_query = $request->start_date_query;
        $finish_date_query = $request->finish_date_query;
        if(!is_null($start_date_query) && !is_null($finish_date_query)){
            $start_date_query = Carbon::parse($start_date_query);
            $finish_date_query = Carbon::parse($finish_date_query);
        }
        $group = Group::find($request->group_id);
        $company = Company::find($request->company_id);
        $analyst = User::find($request->analyst_id);


        //$registros = Delivery::whereIn('id',json_decode($request->chkRegistrosImprimir))->get();

        if(Auth::user()->role->name == "ADMIN" || Auth::user()->role->name == "SUPERADMIN")
        {

        }

        $registros = DB::table('deliveries')
            ->join('users', 'deliveries.user_id', '=', 'users.id')
            ->join('companies', 'deliveries.company_id', '=', 'companies.id')
            ->join('delivery_details', 'deliveries.id', '=', 'delivery_details.delivery_id')
            ->join('items', 'delivery_details.item_id', '=', 'items.id')
            ->join('types', 'items.type_id', '=', 'types.id')
            ->join('sub_groups', 'types.sub_group_id', '=', 'sub_groups.id')
            ->join('groups', 'sub_groups.group_id', '=', 'groups.id')
            ->where(function($query) use($request){
                $query->whereIn('deliveries.id', json_decode($request->chkRegistrosImprimir));
            })            
            ->select(
                'deliveries.description',
                'control_number',
                'items.description as item',
                'deliveries.deliverer as deliverer',
                'groups.name as group',
                'deliveries.dni_deliverer as dni_deliverer',
                'delivery_details.quantity as quantity',
                DB::raw('(delivery_details.quantity * delivery_details.unit_cost_foreign_money) AS total'),
                'companies.name AS company_name'
            );
            dd($registros->toSql());
            if($request->group_id != 0)
            {
                $registros = $registros
                ->where('groups.id', '=' , $request->group_id);
            }

            if($request->company_id != 0)
            {
                $registros = $registros->where('deliveries.company_id', '=', $request->company_id);
            }


        $registros = $registros->groupBy('deliveries.description', 'control_number', 'groups.name', 'item', 'deliverer', 'dni_deliverer','group', 'quantity', 'total', 'company_name')
            ->get()
            ->sortBy('control_number');

        $pdf = PDF::loadView('delivery.pdf_delivery_lst',
                compact('registros', 'start_date_query', 'finish_date_query','group', 'company', 'analyst'));
        //return view('inventory.pdf_inventory', compact('registro', 'details', 'items'));
        return $pdf->stream();
    }

    public function printRegister(Request $request, Delivery $delivery){
        $registro = $delivery;
        $details = $delivery->delivery_details()->orderBy('id')->get();
        $pdf = PDF::loadView('delivery.pdf_delivery', compact('registro', 'details'));
        //return view('inventory.pdf_inventory', compact('registro', 'details', 'items'));
        return $pdf->stream();
    }

}
