<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Worker;
use Illuminate\Support\Facades\DB;

class WorkerController extends Controller
{
    //
    public function getWorker(Request $request){

        $dni = $request->get('dni');

        $response = array();

        if (isset($dni)){

            $dni = strtoupper($dni);

            if (Worker::isDNIFormat($dni)){
                
                $columns = ['id','firstname', 'lastname'];
                
                $worker = Worker::select($columns)
                    ->where('dni', $dni)
                    ->first();
                
                if ($worker){
                    $response[] = array("id" => $worker->id,"value" => $worker->firstname . ' ' . $worker->lastname);
                }
            }
        }

        return response()->json($response);
    }
}
