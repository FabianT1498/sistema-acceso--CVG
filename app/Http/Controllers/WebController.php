<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebController extends Controller
{
	const CREATE = 'create';
	const READ = 'read';
	const EDIT = 'edit';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function successResponse($data, $code=200){
        return response()->json($data, $code);
    }
}
