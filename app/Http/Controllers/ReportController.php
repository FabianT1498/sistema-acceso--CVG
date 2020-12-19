<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GeneratePDFRequest;
use App\Http\Controllers\WebController;

use App\Visit;
use App\Report;

class ReportController extends WebController
{
    //
    public function generatePDF($id, GeneratePDFRequest $request){

        $registros = null;
        $vista = $this::READ;
        $search = request('search');
    
        $columns = [
            'reports.*',
            'visitors.firstname as visitor_firstname',
            'visitors.lastname as visitor_lastname',
            'visitors.dni as visitor_dni',
            'workers.firstname as worker_firstname',
            'workers.lastname as worker_lastname',
            'workers.dni as worker_dni',
            'autos.enrrolment as auto_enrrolment',
            'auto_models.name as auto_model_name',
            'departments.name as department',
            'buildings.name as building'
        ];
       
        $record = Visit::select($columns)
            ->join('visitors', 'visitors.id', '=', 'reports.visitor_id')
            ->join('workers', 'workers.id', '=', 'reports.worker_id')
            ->join('departments', 'departments.id', '=', 'reports.department_id')
            ->join('buildings', 'buildings.id', '=', 'departments.building_id')
            ->leftJoin('autos', 'autos.id', '=', 'reports.auto_id')
            ->leftJoin('auto_models', 'autos.auto_model_id', '=', 'auto_models.id')
            ->where("reports.id", "=", $id)
            ->first();
        
        // Create pass record
        $pass = new Report($record);
        $pass->user_id = Auth::id(); 
        $pass->save();

        $file_name = $record->visitor_firstname. '_' . $record->visitor_lastname.'_'. date('d-m-Y', strtotime($record->date_attendance)). '.pdf';
  
        $pdf = PDF::loadView('report.pass', compact('record'));

        return $pdf->download($file_name);

    }
}
