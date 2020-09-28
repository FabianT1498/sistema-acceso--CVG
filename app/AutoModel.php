<?php

namespace App;

use App\Auto;
use App\AutoBrand;
use Illuminate\Database\Eloquent\Model;

class AutoModel extends Model
{
  //
	protected $fillable = ['name, year , auto_brand_id'];

	public $timestamps = false;

	public function autos(){
		return $this->hasMany(Auto::class);
	}

	public function autoBrand(){
			return $this->belongsTo(AutoBrand::class);
	}
}
