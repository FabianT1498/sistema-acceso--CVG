<?php

namespace App\Http\Controllers;

use App\Group;
use App\Http\Controllers\WebController;
use App\Presentation;
use App\SubGroup;
use App\Type;
use App\Item;
use Illuminate\Http\Request;

class ItemsController extends WebController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $estadisticas = new \stdClass();
        $estadisticas->grupos = Group::count();
        $estadisticas->subGrupos = SubGroup::count();
        $estadisticas->tipos = Type::count();
        $estadisticas->presentaciones = Presentation::count();
        $estadisticas->productos = Item::count();
        return view('items', compact('estadisticas'));
    }
}
