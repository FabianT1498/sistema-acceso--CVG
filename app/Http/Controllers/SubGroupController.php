<?php

namespace App\Http\Controllers;

use App\Group;
use App\SubGroup;
use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SubGroupController extends WebController
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
                $registros = SubGroup::onlyTrashed()->where('name', 'LIKE' ,"%$search%")->get();
            }
            else
            {
            $registros = SubGroup::where('name', 'LIKE' ,"%$search%")->get();
            }
        }else{
            $registros = null;
        }
        return view('sub_group.read', compact('vista', 'trashed', 'search', 'registros'));
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
        return view('sub_group.create', compact('groups', 'trashed', 'vista', 'search'));
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
            'name' => 'required|unique:sub_groups|max:255',
            'group_id' => 'required|max:255'
        ]);

        $search = request('search');
        $trashed = request('trashed');

        if ($validator->fails()) {
            $vista = $this::CREATE;
            $groups = Group::all();
            toastr()->error(__('Error al crear el registro'));
            return redirect()->route('sub_grupos.create', compact('groups', 'trashed', 'vista', 'search'))
                        ->withErrors($validator)
                        ->withInput();
        }
        $sub_group = new SubGroup();
        $sub_group->name = $request->name;
        $sub_group->group_id = $request->group_id;
        $sub_group->save();

        $vista = $this::READ;
        $registros = SubGroup::where('id', $sub_group->id);
        toastr()->success(__('Registro creado con éxito'));
        return redirect()->route('sub_grupos.index', compact('vista', 'trashed', 'search', 'registros'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SubGroup  $sub_group
     * @return \Illuminate\Http\Response
     */
    public function show(SubGroup $sub_group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SubGroup  $sub_group
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $registro = SubGroup::withTrashed()->where('id', $id)->first();
        $vista = $this::EDIT;
        $search = request('search');
        $trashed = request('trashed');
        return view('sub_group.edit', compact('vista', 'trashed', 'search', 'registro'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SubGroup  $sub_group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $sub_group = SubGroup::withTrashed()->where('id', $id)->first();
        $search = request('search');
        $trashed = request('trashed');
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:sub_groups,name,'.$sub_group->id.'|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->route('sub_grupos.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $sub_group->name = $request->name;
        if(!$sub_group->update())
        {
            $search = request('search');
            $trashed = request('trashed');
            $registros = null;
            toastr()->error(__('Error al actualizar el registro'));
            return redirect()->route('sub_grupos.index', compact('vista', 'trashed', 'search', 'registros'));
        }

        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        $registros = SubGroup::where('id', $sub_group->id);
        toastr()->success(__('Registro actualizado con éxito'));
        return redirect()->route('sub_grupos.index', compact('vista', 'trashed', 'search', 'registros'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SubGroup  $sub_group
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sub_group = SubGroup::withTrashed()->where('id', $id)->first();
        $registros = null;
        $sub_group->delete();
        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        toastr()->success(__('Registro eliminado con éxito'));
        return redirect()->route('sub_grupos.index', compact('vista', 'trashed', 'search', 'registros'));
    }


    /**
     * Return a list of subgroups.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function loadSubGroups($group_id)
    {
        $sub_groups = SubGroup::where('group_id', $group_id)->get();
        return $sub_groups;
    }
}
