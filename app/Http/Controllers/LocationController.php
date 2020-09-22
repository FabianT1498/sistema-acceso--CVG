<?php

namespace App\Http\Controllers;

use App\Location;
use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationController extends WebController
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
        $registros = null;
        if (request('buscar')) {
            if($trashed == 1)
            {
                $registros = Location::onlyTrashed()->where('name', 'ILIKE' ,"%$search%");
            }
            else
            {
                $registros = Location::where('name', 'ILIKE' ,"%$search%");
            }
        }
        return view('location.read', compact('vista', 'trashed', 'search', 'registros'));
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
        return view('location.create', compact('vista', 'trashed', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:companies|max:255'
        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }
        $localidad = Location::create(request()->all());
        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        $buscar = true;
        toastr()->success(__('Registro creado con éxito'));
        return redirect()->route('localidades.index', compact('vista', 'trashed', 'search', 'buscar'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $registro = Location::withTrashed()->where('id', $id)->first();
        $vista = $this::EDIT;
        $search = request('search');
        $trashed = request('trashed');
        return view('location.edit', compact('vista', 'trashed', 'search', 'registro'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Location  $localidad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $localidade = Location::withTrashed()->where('id', $id)->first();
        $search = request('search');
        $trashed = request('trashed');
        $buscar = true;
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:companies,name,'.$localidade->id.'|max:255'
        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $localidade->name = $request->name;

        if(!$localidade->update())
        {
            toastr()->error(__('Error al actualizar el registro'));
            return redirect()->route('localidades.index', compact('vista', 'trashed', 'search', 'buscar'));
        }

        $vista = $this::READ;
        toastr()->success(__('Registro actualizado con éxito'));
        return redirect()->route('localidades.index', compact('vista', 'trashed', 'search', 'buscar'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $location = Location::withTrashed()->where('id', $id)->first();
        $location->delete();
        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        $buscar = true;
        toastr()->success(__('Registro eliminado con éxito'));
        return redirect()->route('localidades.index', compact('vista', 'trashed', 'search', 'buscar'));
    }
}
