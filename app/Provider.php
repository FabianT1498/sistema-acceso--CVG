<?php

namespace App;
use App\Invoice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //línea necesaria

class Provider extends Model
{
    use SoftDeletes; //Implementamos
    protected $table = 'companies';
	protected $fillable = ['name', 'dni', 'address', 'phone', 'condition'];
    protected $dates = ['deleted_at']; //Registramos la nueva columna

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
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
