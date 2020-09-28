<?php

namespace App;

use App\User;
use App\Visitor;
use App\PassRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //línea necesaria

class Report extends Model
{
		use SoftDeletes;

    //
		public function user(){
			return $this->belongsTo(User::class);
		}

		public function visitor(){
			return $this->belongsTo(Visitor::class);
		}

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