<?php

namespace App\Http\Controllers;

use App\Auto;
use App\AutoBrand;
use App\AutoModel;

use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'autos.*',
            'auto_models.name as auto_model',
            'auto_brands.name as auto_brand'
        ];

        $auth_user_role = Auth::user()->role_id;

        $autos = Auto::select($columns);
        
        $autos = $autos->join('auto_models', 'auto_models.id', '=', 'autos.auto_model_id')
            ->join('auto_brands', 'auto_brands.id', '=', 'auto_models.auto_brand_id');
        
        if (isset($search) && strlen($search) === 7){
            $search = strtoupper($search);
            $autos = $autos->where("autos.enrrolment", $search);
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
     
        return view('auto.create', compact('trashed', 'vista', 'search'));
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

        $validated = $request->validated();

        $auto_brand = AutoBrand::where('name', $validated['auto_brand'])
                ->first();
                
        $auto_model = AutoModel::where('name', $validated['auto_model'])
            ->first();
     
        if (!$auto_model){
            if (!$auto_brand){
                $auto_brand = new AutoBrand();
                $auto_brand->name = $validated['auto_brand'];
                $auto_brand->save();
            }

            $auto_model = new AutoModel();
            $auto_model->name = $validated['auto_model'];
            $auto_model->auto_brand_id = $auto_brand->id;
            $auto_model->save();
        }

        $auto = new Auto();
        $auto->enrrolment = $validated['auto_enrrolment'];
        $auto->user_id = Auth::user()->id;
        $auto->auto_model_id = $auto_model->id;
        $auto->color = $validated['auto_color'];
       
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
            'autos.*',
            'autos.auto_model_id as model_id',
            'auto_models.name as model',
            'auto_models.auto_brand_id as brand_id',
            'auto_brands.name as brand'
        ];

        $auto = Auto::select($columns)
            ->join('auto_models', 'auto_models.id', '=', 'autos.auto_model_id')
            ->join('auto_brands', 'auto_brands.id', '=', 'auto_models.auto_brand_id')
            ->where("autos.id", "=", $id)
            ->first();
        
        return view('auto.edit', compact('vista', 'search', 'trashed', 'auto'));
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

        $validated = $request->validated();

        $auto->color = $validated['auto_color'];
        
        /* $auto->enrrolment =  $validated['auto_enrrolment'];

        $auto_brand = AutoBrand::where('name', $validated['auto_brand'])
                ->first();
                
        $auto_model = AutoModel::where('name', $validated['auto_model'])
            ->first();
     
        if (!$auto_model){
            if (!$auto_brand){
                $auto_brand = new AutoBrand();
                $auto_brand->name = $validated['auto_brand'];
                $auto_brand->save();
            }

            $auto_model = new AutoModel();
            $auto_model->name = $validated['auto_model'];
            $auto_model->auto_brand_id = $auto_brand->id;
            $auto_model->save();
        } */
      
        if(!$auto->update())
        {
            toastr()->error(__('Error al actualizar el registro'));
        } else {
            toastr()->success(__('Registro actualizado con éxito'));
        }

        return redirect()->route('autos.index', compact('vista', 'search', 'trashed', 'buscar'));
    }

    //

    /* *
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     **/
    public function destroy($id, DestroyAutoRequest $request)
    {
        
        $registros = null;
        $vista = $this::READ;
        $search = request('search');
        $trashed = request('trashed');

        // Delete auto
        $auto = Auto::where('id', $id)->first();
        $auto->delete();
        toastr()->success(__('Auto desactivado con éxito'));

        return redirect()->route('autos.index', compact('vista', 'search', 'trashed'));
    } 
    

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        
        $search = $request['search'];
        $trashed = $request['trashed'] ? true : false;

        // Restore visitor
        $auto = Auto::onlyTrashed()->where('id', $id)->first();

        if ($auto && $auto->restore()){
            toastr()->success(__('Auto reactivado con éxito'));
        } else {
            toastr()->error(__('El auto no ha sido desactivado'));
        }

        return redirect()->route('autos.index', compact('search', 'trashed'));
    } 

    public function getAuto(Request $request){

        $enrrolment = $request->get('enrrolment');

        $response = array();

        if (isset($enrrolment)){
            
            $columns = [
                'autos.id as auto_id',
                'autos.color as color',
                'auto_models.name as model',
                'auto_models.id as auto_model_id',
                'auto_brands.name as brand',
                'auto_brands.id as auto_brand_id'
            ];
            
            $auto = Auto::select($columns)
                ->join('auto_models', 'auto_models.id', '=', 'autos.auto_model_id')
                ->join('auto_brands', 'auto_brands.id', '=', 'auto_models.auto_brand_id')
                ->where('enrrolment', $enrrolment)
                ->first();
            
            if ($auto){
                $response[] = array(
                    "auto_id" => $auto->auto_id,
                    "model" => $auto->model,
                    "auto_model_id" => $auto->auto_model_id,
                    "brand" => $auto->brand,
                    "auto_brand_id" => $auto->auto_brand_id,
                    "color" => $auto->color
                );
            }
        }

        return response()->json($response);
    }

    public function getAutoModels(Request $request){

        $search =  $request->has('search') ? $request->get('search') : '';
        $auto_brand = $request->has('auto_brand') ? $request->get('auto_brand') : '';

        $columns = [
            'auto_models.id as auto_model_id',
            'auto_models.name as auto_model',
            'auto_brands.id as auto_brand_id',
            'auto_brands.name as auto_brand',
        ];

        $auto_models = AutoModel::orderby('auto_models.name','asc')->select($columns);
        
        $auto_models = $auto_models->join('auto_brands', function($query) use ($auto_brand){
            $query->on('auto_brands.id', '=', 'auto_models.auto_brand_id');
            
            if (strlen($auto_brand) > 0){
                $query->where("auto_brands.name", $auto_brand);
            }
        });
        
        
        if (strlen($search) > 0){      
            $auto_models = $auto_models->where("auto_models.name", "LIKE", "%".$search."%");
        }

        $auto_models = $auto_models->limit(5)->get();
    
        $response = array();

        foreach($auto_models as $auto_model){
            $response[] = array(
                "auto_model_id"=>$auto_model->auto_model_id,
                "auto_model"=>ucfirst($auto_model->auto_model),
                "auto_brand_id"=>$auto_model->auto_brand_id,
                "auto_brand"=>ucfirst($auto_model->auto_brand),
                "value"=>ucfirst($auto_model->auto_model)
            );
        }
        
        return response()->json($response);
    }
    
    public function getAutoBrands(Request $request){

        $search =  $request->get('search');

        $columns = ['id','name'];

        $auto_brands = AutoBrand::orderby('name','asc')->select($columns);

        if (strlen($search) > 0){
            $auto_brands = $auto_brands->where("name", "LIKE", "%".$search."%");
        }

        $auto_brands = $auto_brands->limit(5)->get();
    
        $response = array();

        foreach($auto_brands as $auto_brand){
            $response[] = array("id"=>$auto_brand->id, "value"=>ucfirst($auto_brand->name));
        }
        
        return response()->json($response);
    }
}
