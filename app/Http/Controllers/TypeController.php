<?php

namespace App\Http\Controllers;

use App\Group;
use App\SubGroup;
use App\Type;
use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class TypeController extends WebController
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
                $registros = Type::onlyTrashed()->where('name', 'LIKE' ,"%$search%");

            }
            else
            {
                $registros = Type::where('name', 'LIKE' ,"%$search%");
            }
        }else{
            $registros = null;
        }
        return view('type.read', compact('vista', 'search', 'trashed', 'registros'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = Group::all();
        $vista = $this::CREATE;
        $search = request('search');
        $trashed = request('trashed');
        return view('type.create', compact('groups','vista', 'trashed', 'search'));
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
            'name' => 'required|unique:types|max:255'
        ]);

        

        if ($validator->fails()) {
            $vista = $this::EDIT;
            $groups = Group::all();
            toastr()->error(__('Error al crear el registro'));
            return redirect()->route('tipos.create', compact('groups','vista', 'trashed', 'search'))
                        ->withErrors($validator)
                        ->withInput();
        }
        $type = new Type();
        $type->name = $request->name;
        $type->sub_group_id = $request->sub_group_id;

        $type->save();

        $vista = $this::READ;
        $registros = Type::where('id', $type->id);
        toastr()->success(__('Registro creado con éxito'));
        return redirect()->route('tipos.index', compact('vista', 'search', 'trashed',  'registros'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $registro = Type::withTrashed()->where('id',$id)->first();
        $vista = $this::EDIT;
        $search = request('search');
        $trashed = request('trashed');
        return view('type.edit', compact('vista', 'search', 'trashed', 'registro'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $type = Type::withTrashed()->where('id',$id)->first();
        $search = request('search');
        $trashed = request('trashed');
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:types,name,'.$type->id.'|max:255'
        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $type->name = $request->name;
        if(!$type->update())
        {
            $search = request('search');
            $trashed = request('trashed');
            $registros = null;
            toastr()->error(__('Error al actualizar el registro'));
            return redirect()->route('tipos.index', compact('vista', 'search', 'trashed', 'registros'));
        }

        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        $registros = Type::where('id', $type->id);
        toastr()->success(__('Registro actualizado con éxito'));
        return redirect()->route('tipos.index', compact('vista', 'search', 'trashed', 'registros'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $type = Type::withTrashed()->where('id',$id)->first();
        $registros = null;
        $type->delete();
        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        toastr()->success(__('Registro eliminado con éxito'));
        return redirect()->route('tipos.index', compact('vista', 'search', 'trashed', 'registros'));
    }
    
    /**
     * Return a list of subgroups.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function loadTypes($sub_group_id)
    {
        $types = Type::where('sub_group_id', $sub_group_id)->get();
        return $types;
    }
}
