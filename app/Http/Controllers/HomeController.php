<?php

namespace App\Http\Controllers;

use App\Delivery;
use App\Http\Controllers\WebController;
use App\Inventory;
use App\Invoice;
use Illuminate\Http\Request;

class HomeController extends WebController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $anioActual = date('Y');
        $mesActual = date("m");
        $estadisticas = new \stdClass();
        $estadisticas->comprasAnio = Invoice::whereYear('invoice_date', $anioActual)
                            ->count();
        $estadisticas->comprasMes = Invoice::whereYear('invoice_date', $anioActual)
                            ->whereMonth('invoice_date', $mesActual)
                            ->count();
        $estadisticas->entregasAnio = Delivery::whereYear('delivered_date', $anioActual)
                            ->count();
        $estadisticas->entregasMes = Delivery::whereYear('delivered_date', $anioActual)
                            ->whereMonth('delivered_date', $mesActual)
                            ->count();
        $estadisticas->inventariosAnio = Inventory::whereYear('start_date', $anioActual)
                            ->count();
        $estadisticas->inventariosMes = Inventory::whereYear('start_date', $anioActual)
                            ->whereMonth('start_date', $mesActual)
                            ->count();

        return view('home', compact('estadisticas'));
    }
}
