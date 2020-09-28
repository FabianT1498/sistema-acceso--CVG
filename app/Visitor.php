<?php

namespace App;

use App\Report;
use App\Photo;
use App\Auto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //línea necesaria

class Visitor extends Model
{
    use SoftDeletes;
    //
	protected $fillable = ['name', 'dni', 'phone_number'];
		
	public function reports(){
    	return $this->hasMany(Report::class);
    }

    public function autos(){
    	return $this->hasMany(Auto::class);
    }

    public function photo(){
    	return $this->hasOne(Photo::class);
    }
}
