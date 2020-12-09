<?php

namespace App\Http\Controllers;

use App\Visitor;
use App\Visit;
use App\Auto;
use App\Http\Controllers\WebController;
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

        $estadisticas->visitantesAnio = Visitor::whereYear('created_at', $anioActual)
                            ->count();
        $estadisticas->visitasAnio = Visit::whereYear('date_attendance', $anioActual)
                            ->count();
        
        $estadisticas->visitantesMes = Visitor::whereMonth('created_at', $mesActual)
                            ->count();
        $estadisticas->visitasMes = Visit::whereMonth('date_attendance', $mesActual)
                            ->count();
        return view('home', compact('estadisticas'));
    }
}
