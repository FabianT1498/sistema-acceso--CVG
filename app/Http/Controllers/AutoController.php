<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Auto;

class AutoController extends Controller
{
    //

    public function destroy($id){

    	Auto::destroy($id);

    	return response()->json(['success'=>"Auto Deleted successfully.", 'tr'=>'tr_'.$id]);

    }
}
