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

use App\Http\Requests\StoreAutoRequest;
use App\Http\Requests\UpdateAutoRequest;
use App\Http\Requests\DestroyAutoRequest;
use App\Http\Requests\EditAutoRequest;


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
        $trashed = (int) request('trashed');

        $columns = [
            'autos.id as auto_id',
            'autos.user_id as auto_user_id',
            'autos.enrrolment as auto_enrrolment',
            'autos.created_at as auto_created_at',
            'auto_models.name as auto_model_name',
            'visitors.firstname as visitor_firstname',
            'visitors.lastname as visitor_lastname',
        ];

        $autos = null;
        $auth_user_role = Auth::user()->role_id;

        if($trashed && $auth_user_role <= 2){
            $autos = Auto::onlyTrashed()->select($columns);
        } else {
            $autos = Auto::select($columns);
        }

        $autos = $autos->join('visitors', 
                function($query) use ($search){
                    $query->on('visitors.id', '=', 'autos.visitor_id');

                    if (isset($search) && strlen($search) > 0 && $search[0] !== '#'){
                        $search = strtolower($search);
            
                        $isDNI =  (strpos($search, 'v-') !== false || strpos($search, 'e-') !== false) ? true : false;
            
                        if ($isDNI){
                            $query->where(DB::raw('lower(visitors.dni)'), "LIKE", "%".$search);
                        } else {
                            $splitName = explode(' ', $search, 2);
                            $first_name = $splitName[0];
                            $last_name = !empty($splitName[1]) ? $splitName[1] : '';
        
                            $query->where(DB::raw('lower(visitors.firstname)'), "LIKE", "%" . $first_name . "%");
            
                            if ($last_name !== ''){
                                $query->where(DB::raw('lower(visitors.lastname)'), "LIKE", "%" . $last_name . "%");
                            }
                        }   
                    }
                }
            )
            ->join('auto_models', 'auto_models.id', '=', 'autos.auto_model_id');
        
        $isEnrrolment = (isset($search) && ($search[0] === '#' && strlen($search) === 8)) ? true : false;

        if ($isEnrrolment){
            $search =  strtolower(substr($search, 1));
            $autos = $autos->where(DB::raw('lower(autos.enrrolment)'), "LIKE", "%".$search);
        }

        if ($auth_user_role === 3 && !$isEnrrolment){ // TRABAJADOR
            $autos = $autos->where('autos.user_id', Auth::user()->id);
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
    public function store(StoreAutoRequest $request)
    {
      
        $vista = $this::CREATE;
        $search = request('search');
        $trashed = request('trashed');

        $auto = new Auto();
        $auto->enrrolment =  strtoupper($request->get('auto_enrrolment'));
        $auto->color = $request->get('auto_color');
        $auto->visitor_id = $request->get('visitor_id');
        $auto->user_id = Auth::user()->id;

        $auto_brand_chk = (int) $request->check_auto_brand;
        $auto_model_chk = (int) $request->check_auto_model;

        if (isset($auto_brand_chk) && $auto_brand_chk === 1){    
            $auto_brand = new AutoBrand();
            $auto_brand->name = $request->get('auto_brand_input');
            $auto_brand->save();

            $auto_model = new AutoModel();
            $auto_model->name = $request->get('auto_model_input');
            $auto_model->auto_brand_id = $auto_brand->id;
            $auto_model->save();

            $auto->auto_model_id = $auto_model->id;

        } else if (isset($auto_model_chk) && $auto_model_chk === 1){
            $auto_model = new AutoModel();
            $auto_model->name = $request->get('auto_model_input');
            $auto_model->auto_brand_id = $request->get('auto_brand_select');
            $auto_model->save();

            $auto->auto_model_id = $auto_model->id;
        } else {
            $auto->auto_model_id = $request->get('auto_model_select');
        }

        if (!$auto->save()){
            toastr()->error(__('Error al crear el registro'));
        } else {
            toastr()->success(__('Registro creado con exito'));
        }

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
    public function edit($id, EditAutoRequest $request)
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

        $auto = Auto::select($columns)
            ->join('visitors', 'visitors.id', '=', 'autos.visitor_id')
            ->join('auto_models', 'auto_models.id', '=', 'autos.auto_model_id')
            ->join('auto_brands', 'auto_brands.id', '=', 'auto_models.auto_brand_id')
            ->where("autos.id", "=", $id)
            ->first();

        $auto_brands = AutoBrand::orderBy('name', 'asc')->get();

        $auto_models = AutoModel::orderBy('name', 'asc')->where('auto_brand_id', $auto->auto_brand_id)->get();
        
        return view('auto.edit', compact('vista', 'search', 'trashed', 'auto', 'auto_brands', 'auto_models'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAutoRequest $request, $id)
    {
        $vista = $this::READ;
        $search = request('search');
        $trashed = (request('trashed')) ? true : false;
        $buscar = true;

        $auto = Auto::where('id', $id)->first();

        $auto->enrrolment =  strtoupper($request->get('auto_enrrolment'));
        $auto->color = $request->get('auto_color');
        $auto->visitor_id = $request->get('visitor_id');


        $auto_brand_chk = (int) $request->check_auto_brand;
        $auto_model_chk = (int) $request->check_auto_model;

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
        } else {
            toastr()->success(__('Registro actualizado con éxito'));
        }

        return redirect()->route('autos.index', compact('vista', 'search', 'trashed', 'buscar'));
    }

    //

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, DestroyAutoRequest $request)
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
