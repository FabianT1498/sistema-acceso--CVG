<?php

namespace App\Http\Controllers;


use App\User;
use App\Role;

use App\Http\Controllers\WebController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DB;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\DestroyUserRequest;
use App\Http\Requests\RestoreUserRequest;

class UserController extends WebController
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
        $trashed = (int) request('trashed');
        $isDNI = null;

        $user_id = Auth::id();
        $user_role = Auth::user()->role_id;
        
        $users = null;

        $columns = [
            'workers.firstname as firstname',
            'workers.lastname as lastname',
            'workers.dni as dni',
            'workers.email as email',
            'users.id as user_id',
            'users.username as username',
            'users.deleted_at as deleted_at',
            'roles.name as role_name' 
        ];

        if($trashed){
            $users = User::onlyTrashed()->select($columns);
        } else {
            $users = User::select($columns);
        }

        if (strlen($search) > 0){
            $search = strtolower($search);
            $isDNI =  (strpos($search, 'v-') !== false || strpos($search, 'e-') !== false) ? true : false;
        }

        $users = $users
            ->join('workers', function($query) use ($search, $isDNI){
                $query->on('workers.id', '=', 'users.worker_id');

                if (isset($isDNI) && $isDNI){
                    $query->where(DB::raw('lower("dni")'), $search);
                }
            })
            ->join('roles', 'roles.id', '=', 'users.role_id');

        if ($user_role === 1){
            $users = $users->where('users.role_id', '>', 1);
        } else {
            $users = $users->where('users.role_id', '>', 2);
        }

        if (isset($isDNI) && !$isDNI){
            $users->where(DB::raw('lower("username")'), $search);
        }
        
        $users = $users->where('users.id', '!=', $user_id);
        
        $users = $users->paginate(10); 

        return view('user.read', compact('vista', 'trashed', 'search', 'users'));
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
        return view('user.create', compact('roles', 'trashed', 'vista', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreUserRequest  $request
     * @return Response
     */
    public function store(StoreUserRequest $request)
    {
        $search = request('search');
        $trashed = request('trashed');
        $vista = $this::READ;

        $user = new User();
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->role_id = $request->role_id;
        $user->worker_id = $request->worker_id;
        $user->user_id = Auth::user()->id;
        
        if (!$user->save()){
            toastr()->error(__('Ocurrio un error al crear el registro'));
        } else {
            toastr()->success(__('Registro creado con éxito'));
        }

        return redirect()->route('usuarios.index', compact('vista', 'trashed', 'search'));
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
        $vista = $this::EDIT;
        $search = request('search');
        $trashed = request('trashed');

        if (!User::where('id', $id)->first()){
            toastr()->error(__('No existe este usuario'));
            return redirect()->route('usuarios.index', compact('vista', 'trashed', 'search'));
        }

        $columns = [
            'workers.firstname as firstname',
            'workers.lastname as lastname',
            'workers.email as email',
            'workers.dni as dni',
            'users.id as user_id',
            'users.username as username',
            'users.password as password',
            'users.role_id as role_id'
        ];

        $user = User::withTrashed()
            ->select($columns)
            ->join('workers', 'workers.id', '=', 'users.worker_id')
            ->where('users.id', $id)
            ->first();
        
        $roles = (\Auth::user()->role->name == "SUPERADMIN") ? Role::all() : Role::where('id', '>', 2)->get();
        
        return view('user.edit', compact('vista', 'trashed', 'search', 'user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
  
        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');

        $validated = $request->validated();
        
        $user = User::where('id', $id)->first();
   
        if(!$user->update($validated))
        {
            toastr()->error(__('Error al actualizar el registro'));
        } else {
            toastr()->success(__('Registro actualizado con éxito'));
        }

        return redirect()->route('usuarios.index', compact('vista', 'trashed', 'search'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, DestroyUserRequest $request)
    {
        $user = User::withTrashed()->where('id', $id)->first();

        $user->delete();
        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        toastr()->success(__('Usuario desactivado con éxito'));
        return redirect()->route('usuarios.index', compact('vista', 'trashed', 'search'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function restore($id, RestoreUserRequest $request)
    {
        $user = User::onlyTrashed()->where('id', $id)->first();
        $user->restore();

        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        toastr()->success(__('Usuario restaurado con éxito'));
        return redirect()->route('usuarios.index', compact('vista', 'trashed', 'search'));
    }

    public function getUsername(Request $request){

        $username = $request->get('username');

        $response = array();

        if (isset($username)){
       
            $columns = ['username'];
            
            $user = User::select($columns)
                ->where('username', $username)
                ->first();
            
            if ($user){
                $response[] = array("username" => $user->username);
            }
            
        }

        return response()->json($response);
    }
}