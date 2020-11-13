<?php

namespace App\Http\Controllers;


use App\User;
use App\Role;
use Illuminate\Validation\Rule;
use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DB;

use App\Http\Requests\StoreUserRequest;

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
        $trashed = request('trashed');

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
            'roles.name as role_name' 
        ];

        if($trashed){
            $users = User::onlyTrashed()->select($columns);
        } else {
            $users = User::select($columns);
        }

        $users = $users
            ->join('workers', 'workers.id', '=', 'users.worker_id')
            ->join('roles', 'roles.id', '=', 'users.role_id');

        if ($user_role === 1){
            $users = $users->where('users.role_id', '>', 1);
        } else {
            $users = $users->where('users.role_id', '>', 2);
        }

        if (strlen($search) > 0){
            
            $splitName = explode(' ', $search, 2);
            $first_name = $splitName[0];
            $last_name = !empty($splitName[1]) ? $splitName[1] : '';

            $users = $users->where(DB::raw('lower("firstname")'), "LIKE", "%".strtolower($first_name)."%")
                ->orWhere(DB::raw('lower("dni")'), "LIKE", "%".strtolower($search)."%");

            if ($last_name !== ''){
               $users = $users->where(DB::raw('lower("lastname")'), "LIKE", "%".strtolower($last_name)."%");
            }
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
        $vista = $this::EDIT;

        /*
        $auth_user_role = Auth::user()->role_id;
        $new_user_role = (int) $request->get('role_id');
        
         if (($new_user_role && $new_user_role <= 2)
                && $auth_user_role !== 1){
            toastr()->error(__('Error al crear el registro'));
            return redirect()->back()
                ->withInput($request->input());    
        } */

        /* $validation = null;
    
        $worker_id = $request->get('worker_id');

        $rules = [
            'worker_id' => [
                'bail',
                'required',
                'exists:workers,id',
                Rule::unique('users', 'worker_id')->where(function ($query) {
                    return $query->where('deleted_at', NULL);
                })
            ],
            'worker_dni' => [
                'required',
                Rule::exists('workers', 'dni')->where(function ($query) use ($worker_id) {
                    $query->where('id', $worker_id);
                }),
                'max:10'
            ],
            'username' => [
                'required',
                Rule::unique('users', 'username')->where(function ($query) {
                    return $query->where('deleted_at', NULL);
                })
            ],
            'email' => [
                'required',
                Rule::exists('workers', 'email')->where(function ($query) use ($worker_id) {
                    $query->where('id', $worker_id);
                }),
            ],
            'password' => [
                'required',
                'min: 9'
            ],
            'role_id' => [
                'required',
                'exists:roles,id'
            ]
        ]; 

        $validation = Validator::make($request->all(), $rules);    

        if ($validation->fails()) {
            $vista = $this::EDIT;
            toastr()->error(__('Error al crear el registro'));
            return redirect()->route('usuarios.create', compact('trashed', 'vista', 'search'))
                        ->withErrors($validation)
                        ->withInput($request->input());
        }
        */

        $validated = $request->validated();

        $user = new User();
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->role_id = $request->role_id;
        $user->worker_id = $request->worker_id;
        $user->save();

        $vista = $this::READ;
        toastr()->success(__('Registro creado con éxito'));
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
        $columns = [
            'workers.firstname as firstname',
            'workers.lastname as lastname',
            'workers.email as email',
            'workers.dni as dni',
            'users.id as user_id',
            'users.username as username',
            'users.worker_id as worker_id',
            'users.role_id as role_id'
        ];

        $user = User::withTrashed()
            ->select($columns)
            ->join('workers', 'workers.id', '=', 'users.worker_id')
            ->where('users.id', $id)
            ->first();
        
        $roles = (\Auth::user()->role->name == "SUPERADMIN") ? Role::all() : Role::where('id', '>', 2)->get();
        $vista = $this::EDIT;
        $search = request('search');
        $trashed = request('trashed');
        return view('user.edit', compact('vista', 'trashed', 'search', 'user', 'roles'));
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
      
        $user = null;
        $worker_id = (int) $request->get('worker_id');

        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        
        User::withTrashed()
            ->join('workers', 'workers.id', '=', 'users.worker_id')
            ->where('users.id', '=', $id)
            ->first();

        if (!$user || $user->deleted_at || $user->worker_id !== $worker_id){
            toastr()->error(__('Error al actualizar el registro'));
            return redirect()->route('usuarios.index', compact('trashed', 'vista', 'search'));
        }

        

        $auth_user_role = Auth::user()->role_id;
        $new_user_role = (int) $request->get('role_id');
        
        if (($new_user_role && $new_user_role <= 2)
                && $auth_user_role !== 1){
            toastr()->error(__('Error al actualizar el registro'));
            return redirect()->route('usuarios.index', compact('trashed', 'vista', 'search'))
                ->withErrors($validator)
                ->withInput();    
        }

        $rules = [
            'username' => [
                'required',
                Rule::unique('users', 'username')
                    ->ignore($user->id)
                    ->where(function ($query) {
                        return $query->where('deleted_at', NULL);
                    })
            ],
            'role_id' => [
                'required',
                'exists:roles,id'
            ]
        ];

        $password = $request->password;

        if ($password && $password !== ''){
            $rules['password'] = array('required', 'min:9');
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->role_id = $request->role_id;
        
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
        toastr()->success(__('Registro actualizado con éxito'));
        return redirect()->route('usuarios.index', compact('vista', 'trashed', 'search'));
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
        $user->delete();
        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');
        toastr()->success(__('Registro eliminado con éxito'));
        return redirect()->route('usuarios.index', compact('vista', 'trashed', 'search'));
    }

}
