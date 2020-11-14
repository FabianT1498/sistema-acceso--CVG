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

        $user_role = Auth::user()->role_id;

        if(($trashed && $trashed === 1) && $user_role <= 2){
            $visitors = Visitor::onlyTrashed();
        } else {
            $visitors = Visitor::withTrashed();
        }

        if (strlen($search) > 0){

            $splitName = explode(' ', $search, 2);
            $first_name = $splitName[0];
            $last_name = !empty($splitName[1]) ? $splitName[1] : '';

            $visitors = $visitors->where(DB::raw('lower("firstname")'), "LIKE", "%".strtolower($first_name)."%")
                ->orWhere(DB::raw('lower("dni")'), "LIKE", "%".strtolower($search)."%");

            if ($last_name !== ''){
               $visitors = $visitors->where(DB::raw('lower("lastname")'), "LIKE", "%".strtolower($last_name)."%");
            }
        }

        // Trabajador
        if ($user_role === 3){
            $visitors = $visitors->where('created_by')
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
    public function store(Request $request)
    {
        

        $vista = $this::CREATE;
        $search = request('search');
        $trashed = request('trashed');

        $rules = [
            'dni' => ['bail', 'required', 'unique:visitors,dni', 'max:10'],
            'phone_number' => ['required', 'unique:visitors,phone_number'],
            'image' => ['required', 'image' , 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];

        $validation = null;

        $validation = Validator::make($request->all(), $rules); 

        if ($validation->fails()) {
            toastr()->error(__('Error al crear el registro'));
            return redirect()->route('visitantes.create', compact('vista', 'search', 'trashed'))
                ->withErrors($validation)
                ->withInput();
        }

        // Create visitor record
        $visitor = new Visitor();
        $visitor->firstname = $request->firstname;
        $visitor->lastname = $request->lastname;
        $visitor->dni = $request->dni;
        $visitor->phone_number = $request->phone_number;
        $visitor->save();

        // Check if a profile image has been uploaded
        if ($request->has('image')) {
            // Get image file
            $image = $request->file('image');
            // Make a image name based on user name and current timestamp
            $name = Str::slug($visitor->firstname.$visitor->lastname.'_'.time());
            // Define folder path
            $folder = '/images/';
            // Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
            // Upload image
            $this->uploadOne($image, $folder, 'public', $name);

            // set the visitor_id and path on database photo
            $photo = new Photo();
            $photo->path = $filePath;
            $visitor->photo()->save($photo);
        }

        $vista = $this::READ;
        $registros = Visitor::where('id', $visitor->id);
        toastr()->success(__('Registro creado con éxito'));
        return redirect()->route('visitantes.index', compact('vista', 'trashed', 'search', 'registros'));
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

        // This retrieve just one photo
        
        $photo = $visitor->photo()->first();

        $vista = $this::EDIT;
        $search = request('search');
        $trashed = (request('trashed')) ? true : false;

        return view('visitor.edit', compact('vista', 'search', 'trashed', 'visitor', 'photo'));
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
        $vista = $this::EDIT;
        $search = request('search');
        $trashed = request('trashed');

        $visitor = Visitor::withTrashed()->where('id', $id)->first();

        $rules = [
            'dni' => [
                'bail',
                'required',
                Rule::unique('visitors', 'dni')->ignore($visitor->id),
                'max:10'
            ],
            'phone_number' => [
                'required',
                Rule::unique('visitors', 'phone_number')->ignore($visitor->id),
            ],
            'image' => [
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048'
            ],
        ];
              
        $validation = Validator::make($request->all(), $rules);    
    
        if ($validation->fails()) {
            return back()
                ->withErrors($validation)
                ->withInput();
        }

        $visitor->firstname = $request->firstname;
        $visitor->lastname = $request->lastname;
        $visitor->dni = $request->dni;
        $visitor->phone_number = $request->phone_number;

        if(!$visitor->update())
        {
            $search = request('search');
            $trashed = (request('trashed')) ? true : false;
            $buscar = true;
            toastr()->error(__('Error al actualizar el registro'));
            return redirect()->route('visitantes.index', compact('vista', 'search', 'trashed', 'buscar'));
        }

        // Check if a profile image has been uploaded
        if ($request->has('image')) {

            $photo = Photo::where('visitor_id', $visitor->id)->first();
    
            // Delete existing image
            Storage::disk('public')->delete($photo->path);

            // Get image file
            $image = $request->file('image');
            // Make a image name based on user name and current timestamp
            $name = Str::slug($visitor->firstname.$visitor->lastname.'_'.time());
            // Define folder path
            $folder = '/images/';
            // Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
            // Upload image
            $this->uploadOne($image, $folder, 'public', $name);

            // set the visitor_id and path on database photo
            $photo->path = $filePath;
            $photo->update();
        }

        $vista = $this::READ;
        $search = request('search');
        $trashed = (request('trashed')) ? true : false;
        $buscar = true;
        toastr()->success(__('Registro actualizado con éxito'));
        return redirect()->route('visitantes.index', compact('vista', 'search', 'trashed', 'buscar'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $registros = null;
        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');

        // Delete visitor
        $visitor = Visitor::withTrashed()->where('id', $id)->first();
        $visitor->delete();
        toastr()->success(__('Registro eliminado con éxito'));


        return redirect()->route('visitantes.index', compact('vista', 'search', 'trashed'));
    }

    public function getVisitors(Request $request){

        $search =  $request->get('search');
        
        if ($search === ''){
           $visitors = Visitor::orderby('firstname','asc')->select('id','firstname', 'lastname', 'dni')->limit(5)->get();
        }else {
            $arrSearch = explode(" ", $search, 2);

            // if doesn't define the last name 
            if (!isset($arrSearch[1])){
                $arrSearch[] = '';
            }

            $visitors = Visitor::orderby('firstname','asc')
                ->select('id','firstname', 'lastname', 'dni')
                ->where('firstname', 'LIKE', "%$arrSearch[0]%")
                ->where('lastname', 'LIKE', "%$arrSearch[1]%")
                ->limit(5)->get();
        }
  
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
