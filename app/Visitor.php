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

        $this->firstname = isset($attributes['firstname']) ? $attributes['firstname'] : '';
        $this->lastname = isset($attributes['lastname']) ? $attributes['lastname'] : '';
        $this->dni = isset($attributes['dni']) ? strtoupper($attributes['dni']) : '';
        $this->phone_number = isset($attributes['phone_number']) ? $attributes['phone_number'] : '';
    }

    protected $fillable = ['name', 'dni', 'phone_number'];
    
    public static function isDNIFormat(string $nacionality, string $dni){

        return ((strlen($nacionality) === 1 && (strlen($dni) > 0 && strlen($dni) < 10)) 
                    && ($nacionality === 'V' || $nacionality === 'E') 
                            && is_numeric(dni));
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
