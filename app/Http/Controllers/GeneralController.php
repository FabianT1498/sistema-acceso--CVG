<?php

namespace App\Http\Controllers;

use App\Company;
use App\Location;
use App\Provider;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;

class GeneralController extends WebController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $estadisticas = new \stdClass();
        $estadisticas->empresas = Company::count();
        $estadisticas->proveedores = Provider::count();
        $estadisticas->localidades = Location::count();
        return view('general', compact('estadisticas'));
    }
}
