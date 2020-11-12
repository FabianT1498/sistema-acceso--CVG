<?php

namespace App;

use App\Visitor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //línea necesaria

class Auto extends Model
{
		use SoftDeletes;
		
		protected $fillable = ['enrrolment', 'color', 'visitor_id', 'auto_model_id'];

    //
		public function visitor(){
			return $this->belongsTo(Visitor::class);
		}

		/**
		 * Guardar tods la matricula en mayuscula
		 *
		 * @param  string  $value
		 * @return void
		 */
		public function setEnrrolment($value)
		{
			$this->attributes['enrrolment'] = strtoupper($value);
		}
}
