<?php

namespace App\Http\Controllers;

use App\Visitor;
use App\Visit;
use App\Auto;
use App\AutoBrand;
use App\AutoModel;
use App\Photo;
use App\Building;
use App\Department;

use DateTime;

use Illuminate\Validation\Rule;
use App\Http\Controllers\WebController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\Visit\StoreVisitRequest;
use App\Http\Requests\Visit\EditVisitRequest;
use App\Http\Requests\Visit\UpdateVisitRequest;
use App\Http\Requests\Visit\ShowVisitRequest;
use App\Http\Requests\Visit\DestroyVisitRequest;
use App\Http\Requests\Visit\ChangeVisitStatusRequest;

class VisitController extends WebController
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
        $is_my_visit = 0;
        $status_select = request('status_select') ? request('status_select') : 'TODAS' ;
        $start_date = request('start_date') ? request('start_date') : '';
        $finish_date = request('finish_date') ? request('finish_date') : '';

        $today_date = (new DateTime())->format('Y-m-d');

        $columns = [
            'visits.*',
            'visitors.firstname as visitor_firstname',
            'visitors.lastname as visitor_lastname',
            'visitors.dni as visitor_dni',
            'users.username as user_username',
            'workers.firstname as worker_firstname',
            'workers.lastname as worker_lastname',
            'workers.dni as worker_dni',
            'report_visit.id as report_id'
        ];

        $visits = Visit::select($columns);
    
        $visits = $visits->join('visitors',
                function($query) use ($search){
                    $query->on('visitors.id', '=', 'visits.visitor_id');
                    
                    if (strlen($search) > 0){
                        $search = strtolower($search);
            
                        $isDNI =  (strpos($search, 'v-') !== false || strpos($search, 'e-') !== false) ? true : false;
            
                        if ($isDNI){
                            $query->where(DB::raw('lower("dni")'), $search);
                        } else {
                            $splitName = explode(' ', $search, 2);
                            $first_name = $splitName[0];
                            $last_name = !empty($splitName[1]) ? $splitName[1] : '';
            
                            $query->where(DB::raw('lower("firstname")'), "LIKE", "%".$first_name."%");
            
                            if ($last_name !== ''){
                               $query->where(DB::raw('lower("lastname")'), "LIKE", "%".strtolower($last_name)."%");
                            }
                        }   
                    }
                }
            )
            ->join('workers', 'workers.id', '=', 'visits.worker_id')
            ->join('users', 'users.id', '=', 'visits.user_id')
            ->leftJoin(DB::raw("(SELECT DISTINCT ON (visit_id) * FROM reports) as report_visit"),
                    function($join) {
                        $join->on("report_visit.visit_id", "=", "visits.id");
                    }
            );
        
        if ($status_select !== "TODAS"){
            $visits = $visits->where('visits.status', $status_select);
        }

        if ($start_date !== '' && $finish_date !== ''){
            $new_start_date = date('Y-m-d', strtotime($start_date));
            $new_finish_date = date('Y-m-d', strtotime($finish_date));

            $visits = $visits->whereBetween('visits.date_attendance', [$new_start_date, $new_finish_date]);
        }

        $visits = $visits->orderBy('created_at', 'desc')->paginate(10); 

        return view('visit.read', compact('vista', 'status_select', 'search', 'visits', 'start_date', 'finish_date', 'is_my_visit', 'today_date'));
    }

    //
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function myVisits($status = 'TODAS')
    {
        $vista = $this::READ;
        $search = request('search');
        $is_my_visit = 1;
        $start_date = request('start_date') ? request('start_date') : '';
        $finish_date = request('finish_date') ? request('finish_date') : '';
        $status_select = request('status_select') ? request('status_select') : $status;
        $today_date = (new DateTime())->format('Y-m-d');

        $columns = [
            'visits.*',
            'visitors.firstname as visitor_firstname',
            'visitors.lastname as visitor_lastname',
            'visitors.dni as visitor_dni',
            'users.username as user_username',
            'report_visit.id as report_id'
        ];

        $visits = Visit::select($columns);
    
        $visits = $visits->join('visitors',
                function($query) use ($search){
                    $query->on('visitors.id', '=', 'visits.visitor_id');
                    
                    if (strlen($search) > 0){
                        $search = strtolower($search);
            
                        $isDNI =  (strpos($search, 'v-') !== false || strpos($search, 'e-') !== false) ? true : false;
            
                        if ($isDNI){
                            $query->where(DB::raw('lower("dni")'), $search);
                        } else {
                            $splitName = explode(' ', $search, 2);
                            $first_name = $splitName[0];
                            $last_name = !empty($splitName[1]) ? $splitName[1] : '';
            
                            $query->where(DB::raw('lower("firstname")'), "LIKE", "%".$first_name."%");
            
                            if ($last_name !== ''){
                               $query->where(DB::raw('lower("lastname")'), "LIKE", "%".strtolower($last_name)."%");
                            }
                        }   
                    }
                }
            )
            ->join('workers', 'workers.id', '=', 'visits.worker_id')
            ->join('users', 'users.id', '=', 'visits.user_id')
            ->leftJoin(DB::raw("(SELECT DISTINCT ON (visit_id) * FROM reports) as report_visit"),
                    function($join) {
                        $join->on("report_visit.visit_id", "=", "visits.id");
                    }
            );
        
        if ($status_select !== "TODAS"){
            $visits = $visits->where('visits.status', $status_select);
        }

        if ($start_date !== '' && $finish_date !== ''){
            $new_start_date = date('Y-m-d', strtotime($start_date));
            $new_finish_date = date('Y-m-d', strtotime($finish_date));

            $visits = $visits->whereBetween('visits.date_attendance', [$new_start_date, $new_finish_date]);
        }

        $visits = $visits->where('visits.worker_id', Auth::user()->worker_id);
        $visits = $visits->orderBy('created_at', 'desc')->paginate(10); 

        return view('visit.my_visits', compact('vista', 'status_select', 'search', 'visits', 'start_date', 'finish_date', 'is_my_visit', 'today_date'));
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

        // Verifica si el usuario que esta solicitando crear la visita no es un recepcionista de departamento
        $is_my_visit = Auth::user()->role_id !== 4 ? 1 : null;

        if (is_null($is_my_visit)){
            // Si el usuario autenticado es recepcionista de departamento, verificar
            // si la visita es propia o para otro trabajador
            $is_my_visit = !is_null(request('is_my_visit')) 
                    && (request('is_my_visit') === '1' || request('is_my_visit') === '0') 
                ? (int) request('is_my_visit')
                : 0;
        }
            
        $status_select = request('status_select') ? request('status_select') : 'TODAS';
        $start_date = request('start_date') ? request('start_date') : '';
        $finish_date = request('finish_date') ? request('finish_date') : '';

        return view('visit.create', compact('status_select', 'vista', 'search', 'start_date', 'finish_date', 'is_my_visit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVisitRequest $request)
    {
        
        $vista = $this::CREATE;
        $search = request('search');
        $trashed = request('trashed');

        $visitor_id  = (int) $request->visitor_id;
        $worker_id  = (int) $request->worker_id;
        $auto_option = (int) $request->auto_option;

        $validated = $request->validated();

        // Create visit record
        $visit = new Visit();
        $visit->worker_id = $request->worker_id;
        $visit->date_attendance =  $validated['attending_date'];
        $visit->entry_time =  $validated['entry_time'];
        $visit->departure_time =  $validated['departure_time'];
        $visit->status =  Auth::user()->worker_id == $request->worker_id ? "CONFIRMADA" : "POR CONFIRMAR";
        $visit->user_id = Auth::id();

        $building = Building::where('name', $validated['building'])
                ->first();
                
        $department = Department::where('name', $validated['department'])
            ->first();
     
        if (!$department){
            if (!$building){
                $building = new Building();
                $building->name = $validated['building'];
                $building->save();
            }

            $department = new Department();
            $department->name = $validated['department'];
            $department->building_id = $building->id;
            $department->save();
        }

        $visit->department_id =  $department->id;
    
        if ($visitor_id === -1){

            $visitor = new Visitor($validated);
            $visitor->user_id = Auth::id();
            $visitor->save();
  
            if ($request->has('image')){
                // Make a image name based on user name and current timestamp
                $name = Str::slug( $validated['visitor_firstname']. '_' . $validated['visitor_lastname'].'_'. time() );
                $photo = new Photo();
                $photo->storePhoto($validated['image'], $name);
                $visitor->photo()->save($photo);
            }

            $visit->visitor_id = $visitor->id;
        } else {
            $visit->visitor_id = $request->visitor_id;
        }

        if ($auto_option){

            $auto_id = (int) $request->auto_id;
            
            if ($auto_id !== -1){
                $visit->auto_id = $auto_id;
            } else {
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
                $auto->save();

                $visit->auto_id = $auto->id;
            }
        } else {
            $visit->auto_id = null;
        }

        if (!$visit->save()){
            toastr()->error(__('Error al crear el registro'));
        } else {
            toastr()->success(__('Registro creado con éxito'));
        }

        if ($worker_id === Auth::user()->worker_id){
            return redirect()->route('mis_visitas');
        }
                
        return redirect()->route('visitas.index', compact('vista', 'trashed', 'search'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id, ShowVisitRequest $request)
    {
        $start_date = request('start_date') ? request('start_date') : '';
        $finish_date = request('finish_date') ? request('finish_date') : '';
        $search = request('search');
        $status_select = request('status_select') ? request('status_select') : 'TODAS' ;
        $vista = $this::EDIT;

        $visit = $this->getVisitByID($id);

        if ($visit->worker_id === Auth::user()->worker_id){
            $is_my_visit = 1;
        } else {
            $is_my_visit = 0;
        }
        
        return view('visit.show', compact('vista', 'search', 'status_select', 'visit', 'start_date', 'finish_date', 'is_my_visit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id , EditVisitRequest $request)
    {
        $start_date = request('start_date') ? request('start_date') : '';
        $finish_date = request('finish_date') ? request('finish_date') : '';
        $search = request('search');
        $status_select = request('status_select') ? request('status_select') : 'TODAS' ;
        $vista = $this::EDIT;

        $record = $this->getVisitByID($id);

        if ($record->worker_id === Auth::user()->worker_id){
            $is_my_visit = 1;
        } else {
            $is_my_visit = 0;
        }
        
        return view('visit.edit', compact('vista', 'search', 'record', 'start_date', 'finish_date', 'status_select', 'is_my_visit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVisitRequest $request, $id)
    {
        $vista = $this::EDIT;
        $search = request('search');
        $trashed = request('trashed');

        $visitor_id  = (int) $request->visitor_id;
        $worker_id  = (int) $request->worker_id;
        $auto_option = isset($request->auto_option) ? $request->auto_option : 0;

        $validated = $request->validated();

        // Create visit record
        $visit = Visit::find($id);

        $visit->worker_id = $worker_id;
        $visit->date_attendance =  $validated['attending_date'];
        $visit->entry_time =  $validated['entry_time'];
        $visit->departure_time =  $validated['departure_time'];
        $visit->user_id = Auth::id();

        $building = Building::where('name', $validated['building'])
                ->first();
                
        $department = Department::where('name', $validated['department'])
            ->first();
     
        if (!$department){
            if (!$building){
                $building = new Building();
                $building->name = $validated['building'];
                $building->save();
            }

            $department = new Department();
            $department->name = $validated['department'];
            $department->building_id = $building->id;
            $department->save();
        }

        $visit->department_id =  $department->id;
    
        if ($visitor_id === -1){

            $visitor = new Visitor($validated);
            $visitor->user_id = Auth::id();
            $visitor->save();
  
            if ($request->has('image')){
                // Make a image name based on user name and current timestamp
                $name = Str::slug( $validated['visitor_firstname']. '_' . $validated['visitor_lastname'].'_'. time() );
                $photo = new Photo();
                $photo->storePhoto($validated['image'], $name);
                $visitor->photo()->save($photo);
            }

            $visit->visitor_id = $visitor->id;
        } else {
            $visit->visitor_id = $request->visitor_id;
        }

        if ($auto_option){

            $auto_id = (int) $request->auto_id;
            
            if ($auto_id !== -1){
                $visit->auto_id = $auto_id;
            } else {
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
                $auto->save();

                $visit->auto_id = $auto->id;
            }
        } else {
            $visit->auto_id = null;
        }
        
        if (!$visit->update()){
            toastr()->error(__('Error al actualizar  el registro'));
        } else {
            toastr()->success(__('Registro actualizado con éxito'));
        }

        if ($worker_id === Auth::user()->worker_id){
            return redirect()->route('mis_visitas', compact('vista', 'trashed', 'search'));
        }
                
        return redirect()->route('visitas.index', compact('vista', 'trashed', 'search'));
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
        $visit = visit::withTrashed()->where('id', $id)->first();
        $visit->delete();

        toastr()->success(__('Registro eliminado con éxito'));

        return redirect()->route('visitas.index', compact('vista', 'search', 'trashed'));
    }

    
    public function denyVisit($id, ChangeVisitStatusRequest $request){
        $visit = visit::where('id', $id);
        

        if (!$visit->update(['status' => "CANCELADA"])){
            toastr()->error(__('Error al actualizar el registro'));
        } else {
            toastr()->success(__('Cita cancelada con exito'));
        }

        return redirect()->route('mis_visitas');
    }

    public function confirmVisit($id, ChangeVisitStatusRequest $request){
        $visit = visit::where('id', $id);
    
        if (!$visit->update(['status' => "CONFIRMADA"])){
            toastr()->error(__('Error al actualizar el registro'));
        } else {
            toastr()->success(__('Cita confirmada con exito'));
        }

        return redirect()->route('mis_visitas');

    }

    

    public function getDepartments(Request $request){

        $search =  $request->has('search') ? $request->get('search') : '';
        $building = $request->has('building') ? $request->get('building') : '';

        $columns = [
            'departments.id as department_id',
            'departments.name as department',
            'buildings.id as building_id',
            'buildings.name as building',
        ];

        $departments = Department::orderby('departments.name','asc')->select($columns);
        
        $departments = $departments->join('buildings', function($query) use ($building){
            $query->on('buildings.id', '=', 'departments.building_id');
            
            if (strlen($building) > 0){
                $query->where("buildings.name", $building);
            }
        });
        
        
        if (strlen($search) > 0){      
            $departments = $departments->where("departments.name", "LIKE", "%".$search."%");
        }

        $departments = $departments->limit(5)->get();
    
        $response = array();

        foreach($departments as $department){
            $response[] = array(
                "department_id"=>$department->department_id,
                "department"=>ucfirst($department->department),
                "building"=>$department->building,
                "building"=>ucfirst($department->building),
                "value"=>ucfirst($department->department)
            );
        }
        
        return response()->json($response);
    }
    
    public function getBuildings(Request $request){

        $search =  $request->get('search');

        $columns = ['id','name'];

        $buildings = Building::orderby('name','asc')->select($columns);

        if (strlen($search) > 0){
            $buildings = $buildings->where("name", "LIKE", "%".$search."%");
        }

        $buildings = $buildings->limit(5)->get();
    
        $response = array();

        foreach($buildings as $building){
            $response[] = array("id"=>$building->id, "value"=>ucfirst($building->name));
        }
        
        return response()->json($response);
    }

    private function getVisitByID($id){
        $columns = [
            'visits.*',
            'departments.name as department_name',
            'buildings.name as building_name',
            'visitors.id as visitor_id',
            'visitors.firstname as visitor_firstname',
            'visitors.lastname as visitor_lastname',
            'visitors.dni as visitor_dni',
            'workers.id as worker_id',
            'workers.firstname as worker_firstname',
            'workers.lastname as worker_lastname',
            'workers.dni as worker_dni',
            'autos.id as auto_id',
            'autos.enrrolment as auto_enrrolment',
            'autos.color as auto_color',
            'auto_models.name as auto_model',
            'auto_brands.name as auto_brand'
        ];

        return (visit::select($columns)
            ->join('visitors', 'visitors.id', '=', 'visits.visitor_id')
            ->join('workers', 'workers.id', '=', 'visits.worker_id')
            ->join('departments', 'departments.id', '=', 'visits.department_id')
            ->join('buildings', 'buildings.id', '=', 'departments.building_id')
            ->leftJoin('autos', 'autos.id', '=', 'visits.auto_id')
            ->leftJoin('auto_models', 'autos.auto_model_id', '=', 'auto_models.id')
            ->leftJoin('auto_brands', 'auto_models.auto_brand_id', '=', 'auto_brands.id')
            ->where("visits.id", "=", $id)
            ->first());
        
    }

    public function getVisitsByConfirm(Request $request){
        $response = array();
        
        $visits = Visit::where("status", "POR CONFIRMAR")
            ->where("worker_id", Auth::user()->worker_id);

        $response[] = array('visitsByConfirm' => $visits->count());

        return response()->json($response);
    }
}