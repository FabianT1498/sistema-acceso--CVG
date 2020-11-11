<?php

namespace App\Http\Controllers;

use App\Visitor;
use App\Worker;
use App\Report;
use App\PassRecord;
use App\Auto;
use App\AutoBrand;
use App\AutoModel;


use Illuminate\Validation\Rule;
use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AutoController extends WebController
{
    //
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

        $columns = [
            'autos.id as auto_id',
            'autos.enrrolment as auto_enrrolment',
            'autos.created_at as auto_created_at',
            'auto_models.name as auto_model_name',
            'visitors.firstname as visitor_firstname',
            'visitors.lastname as visitor_lastname',
        ];

        $autos = null;

        if($trashed){
            $autos = Auto::onlyTrashed()->select($columns);
        } else {
            $autos = Auto::select($columns);
        }

        $autos = $autos->join('visitors', 'visitors.id', '=', 'autos.visitor_id')
            ->join('auto_models', 'auto_models.id', '=', 'autos.auto_model_id');

        if (strlen($search) > 0){
            $autos = $autos->where('autos.enrrolment', 'LIKE' ,"%$search%")
                ->where('visitors.firstname', 'LIKE' ,"%$search%")
                ->orWhere('visitors.lastname', 'LIKE' ,"%$search%");
        }
        
        $autos = $autos->paginate(10); 

        return view('auto.read', compact('vista', 'trashed', 'search', 'autos'));
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

        $auto_brands = AutoBrand::orderBy('name', 'asc')->get();
     
        return view('auto.create', compact('trashed', 'vista', 'search', 'auto_brands'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request->all();
    
        $vista = $this::CREATE;
        $search = request('search');
        $trashed = request('trashed');

        $visitor_id  =  $request->get('visitor_id');

        $rules = [
            'visitor_id' => ['bail', 'required', 'exists:visitors,id'],
            'visitor_dni' => [
                'required',
                Rule::exists('visitors', 'dni')->where(function ($query) use ($visitor_id) {
                    $query->where('id', $visitor_id );
                }),
                'max:10'
            ],
            'auto_enrrolment' => ['required', 'unique:autos,enrrolment', 'max:7'],
            'auto_color' => ['required']
        ];

        $auto_brand_chk = (int) $request->get('check_auto_brand');
        $auto_model_chk = (int) $request->get('check_auto_model');
        
        if ($auto_brand_chk && $auto_model_chk 
            && $auto_model_chk === 1 && $auto_brand_chk === 1){
            // Cannot checked both checks
            return redirect()->route('autos.create', compact('vista', 'search', 'trashed'))
                ->withErrors(['checks inputs' => 'No puedes seleccionar ambos checkbox']);
        } else if ($auto_brand_chk && $auto_brand_chk === 1){
            $rules['auto_brand_input'] = array('required', 'unique:auto_brands,name', 'min:3');
            $rules['auto_model_input'] = array('required', 'unique:auto_models,name', 'min:3');
        } else if ($auto_model_chk && $auto_model_chk === 1){
            $rules['auto_brand_select'] = array('required', 'exists:auto_brands,id');
            $rules['auto_model_input'] = array('required', 'unique:auto_models,name', 'min:3');
        } else {
            $rules['auto_brand_select'] = array('required', 'exists:auto_brands,id');
            $rules['auto_model_select'] = array('required', 'exists:auto_models,id');
        }

        $validation = Validator::make($request->all(), $rules);    

        if ($validation->fails()) {
            toastr()->error(__('Error al crear el registro'));
            return redirect()->route('autos.create', compact('vista', 'search', 'trashed'))
                ->withErrors($validation)
                ->withInput();
        }

        
        $auto_model = null;
        $auto_brand = null;
        
        $auto = new Auto();
        $auto->enrrolment = $request->get('auto_enrrolment');
        $auto->color = $request->get('auto_color');
        $auto->visitor_id = $request->get('visitor_id');

        if ($auto_brand_chk && $auto_brand_chk === 1){    
            $auto_brand = new AutoBrand();
            $auto_brand->name = $request->get('auto_brand_input');
            $auto_brand->save();

            $auto_model = new AutoModel();
            $auto_model->name = $request->get('auto_model_input');
            $auto_model->auto_brand_id = $auto_brand->id;
            $auto_model->save();

            $auto->auto_model_id = $auto_model->id;

        } else if ($auto_model_chk && $auto_model_chk === 1){
            $auto_model = new AutoModel();
            $auto_model->name = $request->get('auto_model_input');
            $auto_model->auto_brand_id = $request->get('auto_brand_select');
            $auto_model->save();

            $auto->auto_model_id = $auto_model->id;
        } else {
            $auto->auto_model_id = $request->get('auto_model_select');
        }

        $auto->save();

        toastr()->success(__('Registro creado con éxito'));
        return redirect()->route('autos.index', compact('vista', 'trashed', 'search'));
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
        
        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');

        $columns = [
            'autos.id as auto_id',
            'autos.enrrolment as auto_enrrolment',
            'autos.color as auto_color',
            'autos.visitor_id as visitor_id',
            'autos.auto_model_id as auto_model_id',
            'visitors.firstname as visitor_firstname',
            'visitors.lastname as visitor_lastname',
            'visitors.dni as visitor_dni',
            'auto_models.name as auto_model_name',
            'auto_models.auto_brand_id as auto_brand_id',
            'auto_brands.name as auto_brand_name'
        ];

        $auto = null;

        $auto = Auto::select($columns)
            ->join('visitors', 'visitors.id', '=', 'autos.visitor_id')
            ->join('auto_models', 'auto_models.id', '=', 'autos.auto_model_id')
            ->join('auto_brands', 'auto_brands.id', '=', 'auto_models.auto_brand_id')
            ->where("autos.id", "=", $id)
            ->first();

        if (!$auto){
            toastr()->error(__('No existe este auto'));
            return redirect()->route('autos.index', compact('vista', 'search'));
        }

        $auto_brands = AutoBrand::orderBy('name', 'asc')->get();

        $auto_models = AutoModel::orderBy('name', 'asc')->where('auto_brand_id', $auto->auto_brand_id)->get();

        $vista = $this::EDIT;
        $search = request('search');
        $trashed = (request('trashed')) ? true : false;
        
        return view('auto.edit', compact('vista', 'search', 'trashed', 'auto', 'auto_brands', 'auto_models'));
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
        $vista = $this::READ;
        $search = request('search');
        $trashed = (request('trashed')) ? true : false;
        $buscar = true;

        $auto = Auto::where('id', $id)->first();

        if (!$auto){
            toastr()->error(__('No se ha podido actualizar el auto'));
            return redirect()->route('autos.index', compact('vista', 'search'));
        }

        $validation = null;
       
        $visitor_id  =  $request->get('visitor_id');

        $rules = [
            'visitor_id' => ['bail', 'required', 'exists:visitors,id'],
            'visitor_dni' => [
                'required',
                Rule::exists('visitors', 'dni')->where(function ($query) use ($visitor_id) {
                    $query->where('id', $visitor_id );
                }),
                'max:10'
            ],
            'auto_enrrolment' => ['required', Rule::unique('autos', 'enrrolment')->ignore($auto->id), 'max:7'],
            'auto_color' => ['required']
        ];

        $auto_brand_chk = (int) $request->get('check_auto_brand');
        $auto_model_chk = (int) $request->get('check_auto_model');
        
        if ($auto_brand_chk && $auto_model_chk 
            && $auto_model_chk === 1 && $auto_brand_chk === 1){
            // Cannot checked both checks
            return redirect()->route('autos.index', compact('vista', 'search', 'trashed'))
                ->withErrors(['checks inputs' => 'No puedes seleccionar ambos checkbox']);
        } else if ($auto_brand_chk && $auto_brand_chk === 1){
            $rules['auto_brand_input'] = array('required', 'unique:auto_brands,name', 'min:3');
            $rules['auto_model_input'] = array('required', 'unique:auto_models,name', 'min:3');
        } else if ($auto_model_chk && $auto_model_chk === 1){
            $rules['auto_brand_select'] = array('required', 'exists:auto_brands,id');
            $rules['auto_model_input'] = array('required', 'unique:auto_models,name', 'min:3');
        } else {
            $rules['auto_brand_select'] = array('required', 'exists:auto_brands,id');
            $rules['auto_model_select'] = array('required', 'exists:auto_models,id');
        }

        $validation = Validator::make($request->all(), $rules);    

        if ($validation->fails()) {
            return back()
                ->withErrors($validation)
                ->withInput();
        }

        $auto_model = null;
        $auto_brand = null;
  
        $auto->enrrolment = $request->get('auto_enrrolment');
        $auto->color = $request->get('auto_color');
        $auto->visitor_id = $request->get('visitor_id');

        if ($auto_brand_chk && $auto_brand_chk === 1){    
            $auto_brand = new AutoBrand();
            $auto_brand->name = $request->get('auto_brand_input');
            $auto_brand->save();

            $auto_model = new AutoModel();
            $auto_model->name = $request->get('auto_model_input');
            $auto_model->auto_brand_id = $auto_brand->id;
            $auto_model->save();

            $auto->auto_model_id = $auto_model->id;

        } else if ($auto_model_chk && $auto_model_chk === 1){
            $auto_model = new AutoModel();
            $auto_model->name = $request->get('auto_model_input');
            $auto_model->auto_brand_id = $request->get('auto_brand_select');
            $auto_model->save();

            $auto->auto_model_id = $auto_model->id;
        } else {
            $auto->auto_model_id = $request->get('auto_model_select');
        }

        if(!$auto->update())
        {

            toastr()->error(__('Error al actualizar el registro'));
            return redirect()->route('autos.index', compact('vista', 'search', 'trashed', 'buscar'));
        }

        toastr()->success(__('Registro actualizado con éxito'));
        return redirect()->route('autos.index', compact('vista', 'search', 'trashed', 'buscar'));
    }

    //

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

        // Delete auto
        $auto = Auto::where('id', $id)->first();
        $auto->delete();
        toastr()->success(__('Registro eliminado con éxito'));

        return redirect()->route('autos.index', compact('vista', 'search', 'trashed'));
    }

    public function getAutoModels(Request $request){

        $auto_brand_id =  $request->get('auto_brand_id');

        $auto_models = AutoModel::orderby('name','asc')
            ->select(
                'auto_models.id as auto_model_id',
                'auto_models.name as auto_model_name'
            )
            ->where('auto_brand_id', $auto_brand_id)
            ->get();

        $response = array();

        foreach($auto_models as $auto_model){
            $response[] = array(
                "id"=>$auto_model->auto_model_id,
                "value"=>$auto_model->auto_model_name
            );
        }
  
        return response()->json($response);
    }
}
