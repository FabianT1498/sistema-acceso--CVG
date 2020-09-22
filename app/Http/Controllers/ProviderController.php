<?php

namespace App\Http\Controllers;

use App\Provider;
use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProviderController extends WebController
{
    //El campo condition de la tabla companies solo acepta C=Customer o P=Provider
    const PROVIDER = 'P';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

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
                $registros = Provider::onlyTrashed()->where('name', 'ILIKE' ,"%$search%")->where('condition', 'P');
            }
            else
            {
                $registros = Provider::where('name', 'ILIKE' ,"%$search%")->where('condition', 'P');
            }
        }
        return view('provider.read', compact('vista', 'trashed', 'search', 'registros'));
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
        return view('provider.create', compact('vista', 'trashed', 'search'));
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
            'name' => 'required|unique:companies|max:255',
            'dni' => 'required|unique:companies|max:255',
            'address' => 'required|max:255',
            'phone' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }
        $proveedor = Provider::create(request()->all());

        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        $buscar = true;
        toastr()->success(__('Registro creado con éxito'));
        return redirect()->route('proveedores.index', compact('vista', 'trashed', 'search', 'buscar'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $registro = Provider::withTrashed()->where('id', $id)->first();
        $vista = $this::EDIT;
        $search = request('search');
        $trashed = request('trashed');
        return view('provider.edit', compact('vista', 'trashed', 'search', 'registro'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Provider  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $provider = Provider::withTrashed()->where('id', $id)->first();
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:companies,name,'.$provider->id.'|max:255',
            'dni' => 'required|unique:companies,dni,'.$provider->id.'|max:255',
            'address' => 'required|max:255',
            'phone' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $provider->name = $request->name;
        $provider->dni = $request->dni;
        $provider->address = $request->address;
        $provider->phone = $request->phone;
        $search = request('search');
        $trashed = request('trashed');

        if(!$provider->update())
        {
            $buscar = true;
            toastr()->error(__('Error al actualizar el registro'));
            return redirect()->route('proveedores.index', compact('vista', 'trashed', 'search', 'buscar'));
        }

        $vista = $this::READ;
        $buscar = true;
        toastr()->success(__('Registro actualizado con éxito'));
        return redirect()->route('proveedores.index', compact('vista', 'trashed', 'search', 'buscar'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $provider = Provider::withTrashed()->where('id', $id)->first();
        $provider->delete();
        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        $buscar = true;
        toastr()->success(__('Registro eliminado con éxito'));
        return redirect()->route('proveedores.index', compact('vista', 'trashed', 'search', 'buscar'));
    }
}
