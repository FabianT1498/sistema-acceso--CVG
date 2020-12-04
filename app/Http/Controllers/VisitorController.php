<?php

namespace App\Http\Controllers;


use App\Visitor;
use App\Role;
use App\Photo;

use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\StoreVisitorRequest;
use App\Http\Requests\UpdateVisitorRequest;
use App\Http\Requests\DestroyVisitorRequest;
use App\Http\Requests\EditVisitorRequest;

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
        $trashed = (int) request('trashed');

        $auth_user_role = Auth::user()->role_id;

        if($trashed === 1 && $auth_user_role <= 2){
            $visitors = Visitor::onlyTrashed();
        } else {
            $visitors = Visitor::select('*');
        }

        if (strlen($search) > 0){
            $search = strtolower($search);

            $isDNI =  (strpos($search, 'v-') !== false || strpos($search, 'e-') !== false) ? true : false;

            if ($isDNI){
                $visitors = $visitors->where(DB::raw('lower("dni")'), $search);
            } else {
                $splitName = explode(' ', $search, 2);
                $first_name = $splitName[0];
                $last_name = !empty($splitName[1]) ? $splitName[1] : '';

                $visitors = $visitors->where(DB::raw('lower("firstname")'), "LIKE", "%".$first_name."%");

                if ($last_name !== ''){
                   $visitors = $visitors->where(DB::raw('lower("lastname")'), "LIKE", "%".strtolower($last_name)."%");
                }
            }   
        }
        
        $visitors = $visitors->paginate(10); 

        return view('visitor.read', compact('vista', 'trashed', 'search', 'visitors'));
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
        return view('visitor.create', compact('trashed', 'vista', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVisitorRequest $request)
    {
       
        
        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');

        $validated = $request->validated();

        $visitor = new Visitor($validated);
        $visitor->user_id = Auth::user()->id;

        // Make a image name based on user name and current timestamp
        $name = Str::slug( $validated['visitor_firstname']. '_' . $validated['visitor_lastname'].'_'. time() );
        $photo = new Photo();
        $photo->storePhoto($validated['image'], $name);

        if (!$visitor->save() || !$visitor->photo()->save($photo)){
            toastr()->error(__('Ocurrio un error al crear el registro'));
        } else {
            toastr()->success(__('Registro creado con éxito'));
        }
         
        return redirect()->route('visitantes.index', compact('vista', 'trashed', 'search'));
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
    public function edit($id, EditVisitorRequest $request)
    {
        $visitor = Visitor::withTrashed()->where('id',$id)->first();
     
        $photo = $visitor->photo()->first();

        $vista = $this::EDIT;
        $search = $request['search'];
        $trashed = ($request['trashed']) ? true : false;

        return view('visitor.edit', compact('vista', 'search', 'trashed', 'visitor', 'photo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVisitorRequest $request, $id)
    {
        $search = request('search');
        $trashed = (request('trashed')) ? true : false;

        $validated = $request->validated();
   
        $visitor = Visitor::withTrashed()->where('id', $id)->first();

        $visitor->firstname = $validated['visitor_firstname'];
        $visitor->lastname = $validated['visitor_lasttname'];
        $visitor->dni = $validated['visitor_dni'];
        $visitor->phone_number = $validated['visitor_phone_number'];

        // Check if a new profile image has been uploaded
        if ($request->has('image')) {

            $photo = Photo::where('visitor_id', $visitor->id)->first();
    
            // Delete existing image
            Storage::disk('public')->delete($photo->path);

            $name = Str::slug( $validated['visitor_firstname']. '_' . $validated['visitor_lastname'].'_'. time() );
            $photo->storePhoto($validated['image'], $name);
        }

        if(!$visitor->update() || (isset($photo) && !$photo->update()))
        {
            toastr()->error(__('Error al actualizar el registro'));
        } else {
            toastr()->success(__('Registro actualizado con éxito'));
        }

        return redirect()->route('visitantes.index', compact('search', 'trashed'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, DestroyVisitorRequest $request)
    {
        
        $search = $request['search'];
        $trashed = $request['trashed'] ? true : false;

        // Delete visitor
        $visitor = Visitor::withTrashed()->where('id', $id)->first();
        
        if ($visitor && $visitor->delete()){
            toastr()->success(__('Visitante desactivado con éxito'));
        } else {
            toastr()->error(__('No se pudo desactivar el visitante'));
        }
    
        return redirect()->route('visitantes.index', compact('search', 'trashed'));
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function restore($id, RestoreVisitorRequest $request)
    {
        
        $search = $request['search'];
        $trashed = $request['trashed'] ? true : false;

        // Restore visitor
        $visitor = Visitor::onlyTrashed()->where('id', $id)->first();

        if ($visitor && $visitor->restore()){
            toastr()->success(__('Visitante reactivado con éxito'));
        } else {
            toastr()->error(__('No se pudo reactivar el visitante'));
        }

        return redirect()->route('visitantes.index', compact('search', 'trashed'));
    }

    public function getVisitor(Request $request){
        $dni = $request->get('dni');

        $response = array();

        if (isset($dni)){

            $dni = strtoupper($dni);

            if (Visitor::isDNIFormat($dni)){
                
                $columns = ['id','firstname', 'lastname'];
                
                $visitor = Visitor::select($columns)
                    ->where('dni', $dni)
                    ->first();
                
                if ($visitor){
                    $response[] = array("id" => $visitor->id,"value" => $visitor->firstname . ' ' . $visitor->lastname);
                }
            }
        }

        return response()->json($response);
    }

    /* public function getVisitorAutos(Request $request){

        $id =  $request->get('visitorID');

        $autos = Auto::orderby('auto_model','asc')
            ->select(
                'autos.id as auto_id',
                'autos.enrrolment as auto_enrrolment',
                'auto_models.name as auto_model'
            )
            ->where('visitor_id', $id)
            ->leftJoin('auto_models', 'autos.auto_model_id', '=', 'auto_models.id')
            ->get();

        $response = array();

        foreach($autos as $auto){
            $response[] = array(
                "id"=>$auto->auto_id,
                "enrrolment"=>$auto->auto_enrrolment,
                "auto_model" => $auto->auto_model
            );
        }
  
        return response()->json($response);
    } */
}
