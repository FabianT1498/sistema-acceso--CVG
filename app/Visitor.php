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

    public function __construct(array $attributes = array()){
        parent::__construct($attributes);

        $this->firstname = isset($attributes['visitor_firstname']) ? $attributes['visitor_firstname'] : '';
        $this->lastname = isset($attributes['visitor_lastname']) ? $attributes['visitor_lastname'] : '';
        $this->dni = isset($attributes['visitor_dni']) ? strtoupper($attributes['visitor_dni']) : '';
        $this->phone_number = isset($attributes['visitor_phone_number']) ? $attributes['visitor_phone_number'] : '';
        $this->origin = isset($attributes['origin']) ? $attributes['origin'] : '';
    }

    protected $fillable = ['name', 'dni', 'phone_number'];
    
    public static function isDNIFormat(string $dni){

        return ((strlen($dni) > 2 && strlen($dni) <= 10) 
                && ($dni[0] === 'V' || $dni[0] === 'E')
                        && is_numeric(substr($dni, 2)));
    }
		
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
