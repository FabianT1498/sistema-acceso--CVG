<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends WebController
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
                $registros = Company::onlyTrashed()->where('name', 'ILIKE' ,"%$search%")->where('condition', 'C');

            }
            else{
                $registros = Company::where('name', 'ILIKE' ,"%$search%")->where('condition', 'C');
            }
        }
        return view('company.read', compact('vista', 'search', 'trashed', 'registros'));
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
        return view('company.create', compact('vista', 'search', 'trashed'));
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
        $company = Company::create(request()->all());
        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        $buscar = true;
        toastr()->success(__('Registro creado con éxito'));
        return redirect()->route('empresas.index', compact('vista', 'search', 'trashed', 'buscar'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $registro = Company::withTrashed()->where('id', $id)->first();
        $vista = $this::EDIT;
        $search = request('search');
        $trashed = request('trashed');
        return view('company.edit', compact('vista', 'search', 'trashed', 'registro'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $search = request('search');
        $trashed = request('trashed');
        $company = Company::withTrashed()->where('id', $id)->first();
        $buscar = true;
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:companies,name,'.$company->id.'|max:255',
            'dni' => 'unique:companies,dni,'.$company->id.'|max:255',
            'address' => 'max:255',
            'phone' => 'max:255'
        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $company->name = $request->name;
        $company->dni = $request->dni;
        $company->address = $request->address;
        $company->phone = $request->phone;

        if(!$company->update())
        {
            $registros = null;
            toastr()->error(__('Error al actualizar el registro'));
            return redirect()->route('empresas.index', compact('vista', 'search', 'trashed', 'registros'));
        }

        $vista = $this::READ;
        toastr()->success(__('Registro actualizado con éxito'));
        return redirect()->route('empresas.index', compact('vista', 'search', 'trashed', 'buscar'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = Company::withTrashed()->where('id', $id)->first();
        $company->delete();
        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        $buscar = true;
        toastr()->success(__('Registro eliminado con éxito'));
        return redirect()->route('empresas.index', compact('vista', 'search', 'trashed', 'buscar'));
    }
}
