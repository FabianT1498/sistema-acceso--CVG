<?php

namespace App;

use App\Visitor;
use Illuminate\Database\Eloquent\Model;

class Auto extends Model
{
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
