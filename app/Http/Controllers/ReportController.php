<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Report\GeneratePDFRequest;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

use App\Visit;
use App\Report;

use App\Http\Requests\Report\ShowReportRequest;

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
        $start_date = request('start_date') ? request('start_date') : '';
        $finish_date = request('finish_date') ? request('finish_date') : '';

        /**
         * Numero de solicitud o visita
         * Nombre y apellido del visitante
         * Cedula del visitante
         * Fecha de emision
         * Usuario emisor del reporte
        */
        $columns = [
            'reports.*',
            'users.username as user_username',
        ];

        $reports = Report::select($columns)->join('users', 'users.id', '=', 'reports.user_id');
   
        if (strlen($search) > 0){
            $search = strtolower($search);
            $isDNI =  (strpos($search, 'v-') !== false || strpos($search, 'e-') !== false) ? true : false;
            $isNumber = is_numeric($search);

            if ($isDNI){
                $reports = $reports->where(DB::raw('lower("visitor_dni")'), $search);
            } else if($isNumber) {
                $reports = $reports->where('visit_id', $search);
            }   
        }

        if ($start_date !== '' && $finish_date !== ''){
            $new_start_date = date('Y-m-d', strtotime($start_date));
            $new_finish_date = date('Y-m-d', strtotime($finish_date));

            $reports = $reports->whereBetween('reports.created_at', [$new_start_date, $new_finish_date]);
        }

        $reports = $reports->orderBy('reports.created_at', 'desc')->paginate(10); 

        return view('report.read', compact('vista', 'search', 'reports', 'start_date', 'finish_date'));
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id, ShowReportRequest $request)
    {
        $start_date = request('start_date') ? request('start_date') : '';
        $finish_date = request('finish_date') ? request('finish_date') : '';
        $search = request('search');
        $vista = $this::EDIT;

        $columns = [
            'reports.*',
            'users.username as user_username',
        ];

        $report = Report::select($columns)
            ->join('users', 'users.id', '=', 'reports.user_id')
            ->where('reports.id', $id)
            ->first();
       
        return view('report.show', compact('vista', 'search', 'report', 'start_date', 'finish_date'));
    }

    //
    public function generatePDF($id, GeneratePDFRequest $request){

        $registros = null;
        $vista = $this::READ;
        $search = request('search');
    
        $columns = [
            'visits.*',
            'visitors.firstname as visitor_firstname',
            'visitors.lastname as visitor_lastname',
            'visitors.dni as visitor_dni',
            'workers.firstname as worker_firstname',
            'workers.lastname as worker_lastname',
            'workers.dni as worker_dni',
            'autos.enrrolment as auto_enrrolment',
            'autos.color as auto_color',
            'auto_models.name as auto_model_name',
            'departments.name as department',
            'buildings.name as building'
        ];
       
        $record = Visit::select($columns)
            ->join('visitors', 'visitors.id', '=', 'visits.visitor_id')
            ->join('workers', 'workers.id', '=', 'visits.worker_id')
            ->join('departments', 'departments.id', '=', 'visits.department_id')
            ->join('buildings', 'buildings.id', '=', 'departments.building_id')
            ->leftJoin('autos', 'autos.id', '=', 'visits.auto_id')
            ->leftJoin('auto_models', 'autos.auto_model_id', '=', 'auto_models.id')
            ->where("visits.id", "=", $id)
            ->first();
        
        // Create pass record
        $report = new Report(json_decode(json_encode($record), true));
        $report->user_id = Auth::id();

        $record->status = "COMPLETADA";

        if ($report->save() && $record->update()){
            $file_name = $record->visitor_firstname. '_' . $record->visitor_lastname.'_'. date('d-m-Y', strtotime($record->date_attendance)). '.pdf';
            $pdf = PDF::loadView('report.pass', compact('record'));
            return $pdf->download($file_name);
        } else {
            toastr()->error(__('Error al generar el pase'));
        }

        if ($record->worker_id === Auth::user()->worker_id){
            return redirect()->route('mis_visitas');
        }
                
        return redirect()->route('visitas.index', compact('vista', 'trashed', 'search'));
    }
}
