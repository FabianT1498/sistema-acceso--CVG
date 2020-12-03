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
	
	public static function isDNIFormat(string $dni){
        return ((strlen($dni) > 2 && strlen($dni) <= 10) 
                && ($dni[0] === 'V' || $dni[0] === 'E')
                        && is_numeric(substr($dni, 2)));
    }
}
