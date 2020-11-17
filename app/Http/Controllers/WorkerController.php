<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Worker;
use Illuminate\Support\Facades\DB;

class WorkerController extends Controller
{
    //
    public function getWorkers(Request $request){

        $search =  $request->get('search');

        $columns = ['id','firstname', 'lastname', 'dni'];

        $workers = Worker::orderby('firstname','asc')->select($columns);

        if (strlen($search) > 0){
            $search = strtolower($search);
            $splitName = explode(' ', $search, 2);
            $first_name = $splitName[0];
            $last_name = !empty($splitName[1]) ? $splitName[1] : '';

            $workers = $workers->where(DB::raw('lower("firstname")'), "LIKE", "%".$first_name."%");
            
            if ($last_name !== ''){
                $workers = $workers->where(DB::raw('lower("lastname")'), "LIKE", "%".strtolower($last_name)."%");
            }       
        }

        $workers = $workers->limit(5)->get();
    
        $response = array();

        foreach($workers as $worker){
            $response[] = array("id"=>$worker->id,"value"=>$worker->firstname . ' ' . $worker->lastname, "dni"=>$worker->dni);
        }
        
        return response()->json($response);
    }
}
