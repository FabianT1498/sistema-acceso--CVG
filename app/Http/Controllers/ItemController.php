<?php

namespace App\Http\Controllers;

use App\Group;
use App\SubGroup;
use App\Type;
use App\Presentation;
use App\Item;
use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class ItemController extends WebController
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
                $registros = Item::onlyTrashed()->where('description', 'LIKE' ,"%$search%");
            }
            else
            {
                $registros = Item::where('description', 'LIKE' ,"%$search%");
            }
        }else{
            $registros = null;
        }
        return view('item.read', compact('vista', 'trashed', 'search', 'registros'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = Group::all();
        $presentations = Presentation::all();
        $vista = $this::CREATE;
        $search = request('search');
        $trashed = request('trashed');
        return view('item.create', compact('groups', 'trashed', 'presentations','vista', 'search'));
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
            'description' => 'unique:items|max:255'
        ]);


        if ($validator->fails()) {
            $vista = $this::EDIT;
            $groups = Group::all();
            toastr()->error(__('Error al crear el registro'));
            return redirect()->route('productos.create', compact('groups','trashed','vista', 'search'))
                        ->withErrors($validator)
                        ->withInput();
        }
        $item = new Item();
        $item->description = $request->description;
        $item->type_id = $request->type_id;
        $item->presentation_id = $request->presentation_id;

        $item->save();

        $vista = $this::READ;
        $registros = Item::where('id', $item->id);
        toastr()->success(__('Registro creado con éxito'));
        return redirect()->route('productos.index', compact('vista', 'trashed', 'search', 'registros'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $registro = Item::withTrashed()->where('id',$id)->first();
        $vista = $this::EDIT;
        $search = request('search');
        $trashed = request('trashed');
        return view('item.edit', compact('vista', 'trashed', 'search', 'registro'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $item = Item::withTrashed()->where('id',$id)->first();
        $search = request('search');
        $trashed = request('trashed');
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:items,name,'.$item->id.'|max:255'
        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $item->name = $request->name;
        if(!$item->update())
        {
            $search = request('search');
            $trashed = request('trashed');
            $registros = null;
            toastr()->error(__('Error al actualizar el registro'));
            return redirect()->route('productos.index', compact('vista', 'trashed', 'search', 'registros'));
        }

        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        $registros = Item::where('id', $item->id);
        toastr()->success(__('Registro actualizado con éxito'));
        return redirect()->route('productos.index', compact('vista', 'trashed', 'search', 'registros'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::withTrashed()->where('id',$id)->first();
        $registros = null;
        $item->delete();
        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        toastr()->success(__('Registro eliminado con éxito'));
        return redirect()->route('productos.index', compact('vista', 'trashed', 'search', 'registros'));
    }
}
