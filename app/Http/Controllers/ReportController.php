<?php

namespace App\Http\Controllers;

use App\Visitor;
use App\Worker;
use App\Report;
use App\PassRecord;
use App\Auto;
use App\AutoBrand;
use App\AutoModel;
use App\Photo;


use Illuminate\Validation\Rule;
use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Http\Requests\DestroyReportRequest;
use App\Http\Requests\EditReportRequest;
use App\Http\Requests\GeneratePDFRequest;

use PDF;

class ReportController extends WebController
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
            'reports.*',
            'visitors.firstname as visitor_firstname',
            'visitors.lastname as visitor_lastname',
            'visitors.dni as visitor_dni',
            'users.username as user_username'
        ];

        if ($auth_user_role !== 3){ 
            $columns[] = 'workers.firstname as worker_firstname';
            $columns[] = 'workers.lastname as worker_lastname';
            $columns[] = 'workers.dni as worker_dni';
        }

        $auth_user_role = Auth::user()->role_id;

        $reports = Report::select($columns);
    
        $reports = $reports->join('visitors',
                function($query) use ($search){
                    $query->on('visitors.id', '=', 'reports.visitor_id');
                    
                    if (isset($search) && strlen($search) > 2){
                        $search = strtoupper($search);
                        $query->where("visitors.dni", $search);
                    }
                }
            )
            ->join('workers', 'workers.id', '=', 'reports.worker_id')
            ->join('users', 'users.id', '=', 'reports.user_id');
        
        $status = request('status');

        if (isset($status) && $status !== "TODOS"){
            $reports = $reports->where('reports.status', $status);
        }

        if ($auth_user_role === 3){ // TRABAJADOR
            $reports = $reports->where('reports.worker_id', Auth::user()->worker_id);
        }
        
        $reports = $reports->paginate(10); 

        return view('report.read', compact('vista', 'trashed', 'search', 'reports'));
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
        return view('report.create', compact('trashed', 'vista', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReportRequest $request)
    {
        
        $vista = $this::CREATE;
        $search = request('search');
        $trashed = request('trashed');

        $visitor_id  = (int) $request->visitor_id;
        $worker_id  = (int) $request->worker_id;
        $auto_option = (int) $request->auto_option;

        $validated = $request->validated();

        // Create report record
        $report = new Report();
        $report->worker_id = $request->worker_id;
        $report->date_attendance =  $validated['attending_date'];
        $report->entry_time =  $validated['entry_time'];
        $report->departure_time =  $validated['departure_time'];
        $report->user_id = Auth::id();

        if ($visitor_id === -1){

            $visitor = new Visitor($validated);
            $visitor->user_id = Auth::id();

            // Make a image name based on user name and current timestamp
            $name = Str::slug( $validated['visitor_firstname']. '_' . $validated['visitor_lastname'].'_'. time() );
            $photo = new Photo();
            $photo->storePhoto($validated['image'], $name);

            $visitor->save();
            $visitor->photo()->save($photo);

            $report->visitor_id = $visitor->id;
        } else {
            $report->visitor_id = $request->visitor_id;
        }

        if ($auto_option){

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

            $auto_id = (int) $request->auto_id;

            if ($auto_id === -1){
                $auto = new Auto();
                $auto->enrrolment = $validated['auto_enrrolment'];
                $auto->user_id = Auth::user()->id;
                $auto->auto_model_id = $auto_model->id;
                $auto->color = $validated['auto_color'];
                $auto->save();

                $report->auto_id = $auto->id;
            } else {
                $report->auto_id = $auto_id;   
            }
        }
        
        if (!$report->save()){
            toastr()->error(__('Error al crear el registro'));
        } else {
            toastr()->success(__('Registro creado con éxito'));
        }
                
        return redirect()->route('reportes.index', compact('vista', 'trashed', 'search'));
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
    public function edit($id , EditReportRequest $request)
    {
        
        $columns = [
            'reports.id as report_id',
            'reports.date_attendance as date_attendance',
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
            'auto_models.name as auto_model_name'
        ];
       
        $report = Report::select($columns)
            ->join('visitors', 'visitors.id', '=', 'reports.visitor_id')
            ->join('workers', 'workers.id', '=', 'reports.worker_id')
            ->leftJoin('autos', 'autos.id', '=', 'reports.auto_id')
            ->leftJoin('auto_models', 'autos.auto_model_id', '=', 'auto_models.id')
            ->where("reports.id", "=", $id)
            ->first();

        $autos = Auto::select(
                'autos.id as auto_id',
                'autos.enrrolment as auto_enrrolment',
                'auto_models.name as auto_model_name'
            )
            ->join('auto_models', 'auto_models.id', '=', 'autos.auto_model_id')
            ->where('autos.visitor_id', '=', $report->visitor_id)
            ->where('autos.id', '!=', $report->auto_id)
            ->get();

        $vista = $this::EDIT;
        $search = request('search');
        $trashed = (request('trashed')) ? true : false;
        
        return view('report.edit', compact('vista', 'search', 'trashed', 'report', 'autos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateReportRequest $request, $id)
    {
        $vista = $this::READ;
        $search = request('search');
        $trashed = (request('trashed')) ? true : false;
        $buscar = true;

        $report = Report::withTrashed()->where('id', $id)->first();
       
        $attending_date = date('Y-m-d H:i:s', strtotime($request->attending_date));

        $report->visitor_id = $request->visitor_id;
        $report->worker_id = $request->worker_id;
        $auto_id = (int) $request->auto_id;
        $report->auto_id = (isset($auto_id) && $auto_id >= 0) ? $auto_id : null;
        $report->date_attendance =  $attending_date;

        if(!$report->update())
        {
            toastr()->error(__('Error al actualizar el registro'));
        } else {
            toastr()->success(__('Registro actualizado con éxito'));
        }

        return redirect()->route('reportes.index', compact('vista', 'search', 'trashed', 'buscar'));
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
        $report = Report::withTrashed()->where('id', $id)->first();
        $report->delete();

        toastr()->success(__('Registro eliminado con éxito'));

        return redirect()->route('reportes.index', compact('vista', 'search', 'trashed'));
    }

    public function generatePDF($id, GeneratePDFRequest $request){

        $registros = null;
        $vista = $this::READ;
        $search = request('search');
    
        $columns = [
            'reports.date_attendance as date_attendance',
            'visitors.firstname as visitor_firstname',
            'visitors.lastname as visitor_lastname',
            'visitors.dni as visitor_dni',
            'workers.firstname as worker_firstname',
            'workers.lastname as worker_lastname',
            'workers.dni as worker_dni',
            'users.username as user_username',
            'autos.enrrolment as auto_enrrolment',
            'auto_models.name as auto_model_name'
        ];
       
        $record = Report::select($columns)
            ->join('visitors', 'visitors.id', '=', 'reports.visitor_id')
            ->join('workers', 'workers.id', '=', 'reports.worker_id')
            ->join('users', 'users.id', '=', 'reports.user_id')
            ->leftJoin('autos', 'autos.id', '=', 'reports.auto_id')
            ->leftJoin('auto_models', 'autos.auto_model_id', '=', 'auto_models.id')
            ->where("reports.id", "=", $id)
            ->first();
        
        // Create pass record
        $pass = new PassRecord();
        $pass->user_id = Auth::id(); 
        $pass->report_id = $id;
        $pass->save();

        $file_name = $record->visitor_firstname. '_' . $record->visitor_lastname.'_'. $record->date_attendance . '.pdf';
  
        $pdf = PDF::loadView('report.pass', compact('record'));

        return $pdf->download($file_name);

    }

}