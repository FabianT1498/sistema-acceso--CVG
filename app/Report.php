<?php

namespace App;

use App\User;
use App\Visitor;
use App\Worker;
use App\Auto;
use App\PassRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //línea necesaria

class Report extends Model
{
	use SoftDeletes;

	protected $nullable = ['auto_id'];

	protected $dates = [
        'date_attendance',
	];
	
    //
	public function user(){
		return $this->belongsTo(User::class);
	}

	public function visitor(){
		return $this->belongsTo(Visitor::class);
	}

	public function worker(){
		return $this->belongsTo(Worker::class);
	}

	public function auto(){
		return $this->belongsTo(Auto::class);
	}

	/**
	 * Este método establece la relación n:n entre los usuarios 
	 * y los reportes en la tabla pass_record.
	 */
    public function issuersUsers()
    {
	    return $this->belongsToMany(User::class, 'pass_record')
	      ->withTimestamps()
	      ->using(PassRecord::class)
	      ->withPivot([
	          'created_at',
	          'updated_at',
	      ]);
	}
}