<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Worker;

class WorkerController extends Controller
{
    //
    public function getWorkers(Request $request){

        $search =  $request->get('search');

        if ($search === ''){
           $workers = Worker::orderby('firstname','asc')->select('id','firstname', 'lastname', 'dni')->limit(5)->get();
        }else{
            
            $workers = Worker::orderby('firstname','asc')
                ->select('id','firstname', 'lastname', 'dni')
                ->where('firstname', 'LIKE', "%$search%")
                ->limit(5)->get();
        }
  
        $response = array();
        foreach($workers as $worker){
           $response[] = array("id"=>$worker->id,"value"=>$worker->firstname . ' ' . $worker->lastname, "dni"=>$worker->dni);
        }
  
        return response()->json($response);
    }
}
