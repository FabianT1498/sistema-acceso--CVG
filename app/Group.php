<?php

namespace App;
use App\SubGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //línea necesaria

class Group extends Model
{
    use SoftDeletes; //Implementamos

	protected $fillable = ['name'];
    protected $dates = ['deleted_at']; //Registramos la nueva columna

    public function sub_groups()
    {
	return $this->hasMany(SubGroup::class);
    }

    /***************************************/
    /***************************************/
    /****** Accessors y Mutators **********/
    /***************************************/
    /***************************************/
    /**
     * Colocar en Mayuscula la primera letra de Name
     *
     * @param  string  $value
     * @return string
     */
    public function getNameAttribute($value)
    {
        return \ucwords($value);
    }

    /**
     * Guardar todo el Name en Minuscula
     *
     * @param  string  $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }
}
