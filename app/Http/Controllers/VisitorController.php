<?php

namespace App\Http\Controllers;


use App\Visitor;
use App\Role;
use App\Auto;
use App\AutoModel;
use App\Photo;
use App\Traits\UploadTrait;

use Illuminate\Validation\Rule;
use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
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

    use UploadTrait;

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

        $visitors = null;

        $auth_user_role = Auth::user()->role_id;

        if(($trashed && $trashed === 1) && $auth_user_role <= 2){
            $visitors = Visitor::onlyTrashed();
        } else {
            $visitors = Visitor::withTrashed();
        }

        if (strlen($search) > 0){
            $search = strtolower($search);

            $isDNI =  (strpos($search, 'v-') !== false || strpos($search, 'e-') !== false) ? true : false;

            if ($isDNI){
                $visitors = $visitors->where(DB::raw('lower("dni")'), "LIKE", "%".$search);
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

        if ($auth_user_role === 3 && ((isset($isDNI) && !$isDNI) || !isset($isDNI))){ // TRABAJADOR
            $visitors = $visitors->where('visitors.user_id', Auth::user()->id);
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

        $visitor = new Visitor($request->validated());
        $visitor->user_id = Auth::user()->id;

        // Get image file
        $image = $request->file('image');
     
        // Make a image name based on user name and current timestamp
        $name = Str::slug( $request->firstname. '_' . $request->lastname.'_'. time() );

        // Define folder path
        $folder = '/images/';

        // Make a file path where image will be stored [ folder path + file name + file extension]
        $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();

        // Upload image
        $this->uploadOne($image, $folder, 'public', $name);

        $photo = new Photo();
        $photo->path = $filePath;
        
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
   
        $visitor = Visitor::withTrashed()->where('id', $id)->first();

        $visitor->firstname = $request->firstname;
        $visitor->lastname = $request->lastname;
        $visitor->dni = strtoupper($request->dni);
        $visitor->phone_number = $request->phone_number;

        // Check if a new profile image has been uploaded
        if ($request->has('image')) {

            $photo = Photo::where('visitor_id', $visitor->id)->first();
    
            // Delete existing image
            Storage::disk('public')->delete($photo->path);

            // Get image file
            $image = $request->file('image');

            // Make a image name based on user name and current timestamp
            $name = Str::slug( $request->firstname. '_' . $request->lastname.'_'. time() );

            // Define folder path
            $folder = '/images/';

            // Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();

            // Upload image
            $this->uploadOne($image, $folder, 'public', $name);

            // set the new path on database photo
            $photo->path = $filePath;

            $photo->update();
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
        $visitor->delete();
        toastr()->success(__('Registro eliminado con éxito'));

        return redirect()->route('visitantes.index', compact('search', 'trashed'));
    }

    public function getVisitors(Request $request){

        $search =  $request->get('search');

        $columns = ['id','firstname', 'lastname', 'dni'];

        if ($search === ''){
            $visitors = Visitor::orderby('firstname','asc')->select($columns);
        } else {
            $splitName = explode(' ', $search, 2);
            $first_name = $splitName[0];
            $last_name = !empty($splitName[1]) ? $splitName[1] : '';
    
            $visitors = Visitor::orderby('firstname','asc')
                ->select($columns)
                ->where(DB::raw('lower("firstname")'), "LIKE", "%".strtolower($first_name)."%");
    
            if ($last_name !== ''){
                $visitors = $visitors->where(DB::raw('lower("lastname")'), "LIKE", "%".strtolower($last_name)."%");
            }
        }

        $route = $request->get('route');

        if (Auth::user()->role_id === 3 && (isset($route) && $route === 'autos')){
            $visitors = $visitors->where('user_id', Auth::user()->id);
        }

        $visitors = $visitors->limit(5)->get();

        $response = array();

        foreach($visitors as $visitor){
           $response[] = array("id"=>$visitor->id,"value"=>$visitor->firstname . ' ' . $visitor->lastname, "dni" => $visitor->dni);
        }
  
        return response()->json($response);
    }

    public function getVisitorAutos(Request $request){

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
    }
}
