<?php

namespace App\Http\Controllers;

use App\Presentation;
use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PresentationController extends WebController
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
        if (request('buscar')) {
            if($trashed == 1)
            {
                $registros = Presentation::onlyTrashed()->where('name', 'LIKE' ,"%$search%");
            }
            else
            {
                $registros = Presentation::where('name', 'LIKE' ,"%$search%");
            }
        }else{
            $registros = null;
        }
        return view('presentation.read', compact('vista', 'trashed','search', 'registros'));
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
        return view('presentation.create', compact('vista', 'trashed','search'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $search = request('search');
        $trashed = request('trashed');
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:presentations|max:255'
        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }
        $presentacion = Presentation::create(request()->all());

        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        $registros = Presentation::where('id', $presentacion->id);
        toastr()->success(__('Registro creado con éxito'));
        return redirect()->route('presentaciones.index', compact('vista', 'trashed','search', 'registros'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Presentation  $presentation
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Presentation  $presentation
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $registro =  Presentation::withTrashed()->where('id', $id)->first();
        $vista = $this::EDIT;
        $search = request('search');
        $trashed = request('trashed');
        return view('presentation.edit', compact('vista', 'trashed','search', 'registro'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Presentation  $presentacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $presentacion = Presentation::withTrashed()->where('id', $id)->first();
        $search = request('search');
        $trashed = request('trashed');
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:presentations,name,'.$presentacion->id.'|max:255'
        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $presentacion->name = $request->name;
        if(!$presentacion->update())
        {
            $search = request('search');
            $trashed = request('trashed');
            $registros = null;
            toastr()->error(__('Error al actualizar el registro'));
            return redirect()->route('presentaciones.index', compact('vista', 'trashed','search', 'registros'));
        }

        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        $registros = Presentation::where('id', $presentacion->id);
        toastr()->success(__('Registro actualizado con éxito'));
        return redirect()->route('presentaciones.index', compact('vista', 'trashed','search', 'registros'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Presentation  $presentation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $presentation = Presentation::withTrashed()->where('id', $id)->first();
        $registros = null;
        $presentation->delete();
        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        toastr()->success(__('Registro eliminado con éxito'));
        return redirect()->route('presentaciones.index', compact('vista', 'trashed','search', 'registros'));
    }
}
