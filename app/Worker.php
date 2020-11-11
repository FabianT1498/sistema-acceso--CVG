<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Report;
use Illuminate\Database\Eloquent\SoftDeletes; //línea necesaria

class Worker extends Model
{
	public $timestamps = false;
   	
	public function reports(){
    	return $this->hasMany(Report::class);
    }
}
