<?php

namespace App\Http\Controllers;

use App\Group;
use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupController extends WebController
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
                $registros = Group::onlyTrashed()->where('name', 'ILIKE' ,"%$search%");
            }
            else
            {
                $registros = Group::where('name', 'ILIKE' ,"%$search%");
            }
        }
        return view('group.read', compact('vista', 'trashed', 'search', 'registros'));
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
        return view('group.create', compact('vista', 'trashed', 'search'));
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
            'name' => 'required|unique:groups|max:255'
        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }
        $group = Group::create(request()->all());

        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        $buscar = true;
        toastr()->success(__('Registro creado con éxito'));
        return redirect()->route('grupos.index', compact('vista', 'trashed', 'search', 'buscar'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $registro = Group::withTrashed()->where('id', $id)->first();
        $vista = $this::EDIT;
        $search = request('search');
        $trashed = request('trashed');
        return view('group.edit', compact('vista', 'trashed', 'search', 'registro'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $search = request('search');
        $trashed = request('trashed');
        $group = Group::withTrashed()->where('id', $id)->first();
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:groups,name,'.$group->id.'|max:255'
        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $group->name = $request->name;
        if(!$group->update())
        {
            $search = request('search');
            $trashed = request('trashed');
            $buscar = true;
            toastr()->error(__('Error al actualizar el registro'));
            return redirect()->route('grupos.index', compact('vista', 'trashed', 'search', 'buscar'));
        }

        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        $buscar = true;
        toastr()->success(__('Registro actualizado con éxito'));
        return redirect()->route('grupos.index', compact('vista', 'trashed', 'search', 'buscar'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $group = Group::withTrashed()->where('id', $id)->first();
        $group->delete();
        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        $buscar = true;
        toastr()->success(__('Registro eliminado con éxito'));
        return redirect()->route('grupos.index', compact('vista', 'trashed', 'search', 'buscar'));
    }
}
