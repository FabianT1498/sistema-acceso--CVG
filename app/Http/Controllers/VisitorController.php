<?php

namespace App\Http\Controllers;


use App\Visitor;
use App\Role;
use App\Auto;
use App\AutoModel;
use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class VisitorController extends WebController
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
                $registros = Visitor::onlyTrashed()->where('firstname', 'LIKE' ,"%$search%")
                ->where('lastname', 'LIKE', "%$search%")
                ->where('dni', 'LIKE', "%$search%");
             
            }
            else
            {
                $registros = Visitor::where('firstname', 'LIKE' ,"%$search%")
                ->orWhere('lastname', 'LIKE', "%$search%")
                ->orWhere('dni', 'LIKE', "%$search%");
            }
            
        }else{
            $registros = null;
        }
        return view('visitor.read', compact('vista', 'trashed', 'search', 'registros'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $roles = (\Auth::user()->role->name === "SUPERADMIN") ? Role::all() : Role::where('id', '>', 2)->get();
        $vista = $this::CREATE;
        $search = request('search');
        $trashed = request('trashed');
        return view('visitor.create', compact('roles', 'trashed', 'vista', 'search'));
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
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'dni' => 'required|unique:users|max:255',
        ]);


        if ($validator->fails()) {
            $vista = $this::EDIT;
            $roles = (\Auth::user()->role->name === "SUPERADMIN") ? Role::all() : Role::where('id', '>', 2)->get();
            toastr()->error(__('Error al crear el registro'));
            return redirect()->route('usuarios.create', compact('roles', 'trashed', 'vista', 'search'))
                        ->withErrors($validator)
                        ->withInput();
        }
        $user = new User();
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->dni = $request->dni;
        if($request->role_id <= 2 && \Auth::user()->role->name !== "SUPERADMIN")
        {
            $vista = $this::EDIT;
            $roles = (\Auth::user()->role->name == "SUPERADMIN") ? Role::all() : Role::where('id', '>', 2)->get();
            toastr()->error(__('No lo vuelva a hacer.   '));
            return redirect()->route('usuarios.create', compact('roles', 'trashed', 'vista', 'search'))
                        ->withErrors($validator)
                        ->withInput();
        }
        $user->role_id = $request->role_id;

        $user->password = Hash::make($request->password);

        $user->save();

        $vista = $this::READ;
        $registros = User::where('id', $user->id);
        toastr()->success(__('Registro creado con éxito'));
        return redirect()->route('usuarios.index', compact('vista', 'trashed', 'search', 'registros'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $visitor = Visitor::withTrashed()->where('id',$id)->first();
        $autos = Auto::where('visitor_id', $id)
            ->leftJoin('auto_models', 'autos.auto_model_id', '=', 'auto_models.id');

       /* $auto_models =  DB::table('auto_models')
                     ->select(DB::raw('*'))
                     ->where(DB::raw("(SELECT auto_model_id FROM autos WHERE visitor_id={$id})"), '!=', 'auto_models.id')
                     ->get();*/

        $auto_models = AutoModel::get();

        $vista = $this::EDIT;
        $search = request('search');
        $trashed = (request('trashed')) ? true : false;
        return view('visitor.edit', compact('vista', 'search', 'trashed', 'visitor', 'autos', 'auto_models'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $visitor = Visitor::withTrashed()->where('id', $id)->first();
        $search = request('search');
        $trashed = request('trashed');

       return $request->all();


        $validator = Validator::make($request->all(), [
            'visitor.dni' => 'dni|unique:visitors',
            'visitor.phone_number' => 'phone_number|unique:visitors',
            'auto.*.enrrolment'

        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->dni = $request->dni;
        if($request->role_id <= 2 && \Auth::user()->role->name !== "SUPERADMIN")
        {
            $vista = $this::EDIT;
            $roles = (\Auth::user()->role->name == "SUPERADMIN") ? Role::all() : Role::where('id', '>', 2)->get();
            toastr()->error(__('No lo vuelva a hacer.   '));
            return redirect()->route('usuarios.create', compact('roles', 'trashed', 'vista', 'search'))
                        ->withErrors($validator)
                        ->withInput();
        }
        $user->role_id = $request->role_id;
        if($request->password != '')
        {
            $user->password = Hash::make($request->password);        
        }
        if(!$user->update())
        {
            $search = request('search');
            $trashed = request('trashed');
            $registros = null;
            toastr()->error(__('Error al actualizar el registro'));
            return redirect()->route('usuarios.index', compact('vista', 'trashed', 'search', 'registros'));
        }

        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        $registros = User::where('id', $user->id);
        toastr()->success(__('Registro actualizado con éxito'));
        return redirect()->route('usuarios.index', compact('vista', 'trashed', 'search', 'registros'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::withTrashed()->where('id', $id)->first();
        $registros = null;
        $user->delete();
        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        toastr()->success(__('Registro eliminado con éxito'));
        return redirect()->route('usuarios.index', compact('vista', 'trashed', 'search', 'registros'));
    }
}
