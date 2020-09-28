<?php

namespace App;

use App\AutoModel;
use Illuminate\Database\Eloquent\Model;

class AutoBrand extends Model
{
		public $timestamps = false;

    //
		public function autoModels(){
			return $this->hasMany(AutoModel::class);
		}
}