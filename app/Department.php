<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Building;


class Department extends Model
{
    //
    protected $fillable = ['name'];

	public $timestamps = false;

	public function building(){
			return $this->belongsTo(Building::class);
	}
}
