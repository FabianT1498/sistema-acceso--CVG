<?php

namespace App\Http\Controllers;

use App\Visitor;
use App\Worker;
use App\Report;
use App\PassRecord;
use App\Auto;

use Illuminate\Validation\Rule;
use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

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
        $trashed = request('trashed');

        $columns = [
            'reports.*',
            'visitors.firstname as visitor_firstname',
            'visitors.lastname as visitor_lastname',
            'workers.firstname as worker_firstname',
            'workers.lastname as worker_lastname',
            'users.username as user_username'
        ];

        $reports = null;

        if($trashed){
            $reports = Report::onlyTrashed()->select($columns);
        } else {
            $reports = Report::select($columns);
        }

        $reports = $reports->join('visitors', 'visitors.id', '=', 'reports.visitor_id')
            ->join('workers', 'workers.id', '=', 'reports.worker_id')
            ->join('users', 'users.id', '=', 'reports.user_id');

        if (strlen($search) > 0){
            $reports = $reports->where('visitors.firstname', 'LIKE' ,"%$search%")
                ->orWhere('visitors.lastname', 'LIKE' ,"%$search%");
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
    public function store(Request $request)
    {
        
        $vista = $this::CREATE;
        $search = request('search');
        $trashed = request('trashed');

        $validation = null;
       
        $visitor_id  =  $request->get('visitor_id');
        $worker_id = $request->get('worker_id');

        $rules = [
            'visitor_id' => ['bail', 'required', 'exists:visitors,id'],
            'visitor_dni' => [
                'required',
                Rule::exists('visitors', 'dni')->where(function ($query) use ($visitor_id) {
                    $query->where('id', $visitor_id );
                }),
                'max:10'
            ],
            'worker_id' => ['required', 'exists:workers,id'],
            'worker_dni' => [
                'required',
                Rule::exists('workers', 'dni')->where(function ($query) use ($worker_id) {
                    $query->where('id', $worker_id);
                }),
                'max:10'
            ],
            'attending_date' => [
                'required',
                'date_format:Y-m-d H:i',
            ]
        ];

        $auto_id = (int) $request->get('auto_id');

        if (!is_null($auto_id) && $auto_id >= 0){
            $rules[] = array(
                'auto_id' => [
                    Rule::exists('autos', 'id')->where(function ($query) use ($visitor_id) {
                        $query->where('visitor_id', $visitor_id);
                    })   
                ]
            );
        }

        $validation = Validator::make($request->all(), $rules);    

        if ($validation->fails()) {
            toastr()->error(__('Error al crear el registro'));
            return redirect()->route('reportes.create', compact('vista', 'search', 'trashed'))
                ->withErrors($validation)
                ->withInput();
        }

        $attending_date = date('Y-m-d H:i:s', strtotime($request->get('attending_date')));

        // Create report record
        $report = new Report();
        $report->user_id = Auth::id(); 
        $report->visitor_id = $request->get('visitor_id');
        $report->worker_id = $request->get('worker_id');
        $report->auto_id = !is_null($auto_id) && $auto_id >= 0 ? $auto_id : null;
        $report->date_attendance =  $attending_date;
        $report->save();
        
        $vista = $this::READ;
        $reports = Report::select(
            'reports.*',
            'visitors.firstname as visitor_firstname',
            'visitors.lastname as visitor_lastname',
            'workers.firstname as worker_firstname',
            'workers.lastname as worker_lastname',
            'users.username as user_username'
        )
            ->join('visitors', 'visitors.id', '=', 'reports.visitor_id')
            ->join('workers', 'workers.id', '=', 'reports.worker_id')
            ->join('users', 'users.id', '=', 'reports.user_id')
            ->paginate(10);

        toastr()->success(__('Registro creado con éxito'));
        return redirect()->route('reportes.index', compact('vista', 'trashed', 'search', 'reports'));
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

        if (!$report){
            toastr()->error(__('No existe este reporte'));
            return redirect()->route('reportes.index', compact('vista', 'search'));
        }

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
    public function update(Request $request, $id)
    {
        $vista = $this::READ;
        $search = request('search');
        $trashed = (request('trashed')) ? true : false;
        $buscar = true;

        $report = Report::withTrashed()->where('id', $id)->first();

        if (!$report){
            toastr()->error(__('No se ha podido actualizar el reporte'));
            return redirect()->route('reportes.index', compact('vista', 'search'));
        }

        $validation = null;
       
        $visitor_id  =  $request->get('visitor_id');
        $worker_id = $request->get('worker_id');

        $rules = [
            'visitor_id' => ['bail', 'required', 'exists:visitors,id'],
            'visitor_dni' => [
                'required',
                Rule::exists('visitors', 'dni')->where(function ($query) use ($visitor_id) {
                    $query->where('id', $visitor_id );
                }),
                'max:10'
            ],
            'worker_id' => ['required', 'exists:workers,id'],
            'worker_dni' => [
                'required',
                Rule::exists('workers', 'dni')->where(function ($query) use ($worker_id) {
                    $query->where('id', $worker_id);
                }),
                'max:10'
            ],
            'attending_date' => [
                'required',
                'date_format:Y-m-d H:i',
            ]
        ];

        $auto_id = (int) $request->get('auto_id');

        if (!is_null($auto_id) && $auto_id >= 0){
            $rules[] = array(
                'auto_id' => [
                    Rule::exists('autos', 'id')->where(function ($query) use ($visitor_id) {
                        $query->where('visitor_id', $visitor_id);
                    })   
                ]
            );
        }

        $validation = Validator::make($request->all(), $rules);    

        if ($validation->fails()) {
            return back()
                ->withErrors($validation)
                ->withInput();
        }

        $attending_date = date('Y-m-d H:i:s', strtotime($request->attending_date));

        $report->user_id = Auth::id(); 
        $report->visitor_id = $request->visitor_id;
        $report->worker_id = $request->worker_id;
        $report->auto_id = !is_null($auto_id) && $auto_id >= 0 ? $auto_id : null;
        $report->date_attendance =  $attending_date;

        if(!$report->update())
        {

            toastr()->error(__('Error al actualizar el registro'));
            return redirect()->route('reportes.index', compact('vista', 'search', 'trashed', 'buscar'));
        }

        toastr()->success(__('Registro actualizado con éxito'));
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

    public function generatePDF($id){

        $registros = null;
        $vista = $this::READ;
        $search = request('search');
    
        $report = Report::where('id', $id)->first();

        if (!$report){
            toastr()->error(__('No existe este reporte'));
            return redirect()->route('reportes.index', compact('vista', 'search'));
        }

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
  
        $pdf = PDF::loadView('report.pass', compact('record'));

        return $pdf->download('pase_entrada.pdf');

    }

}