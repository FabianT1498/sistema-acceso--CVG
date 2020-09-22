<?php

namespace App;
use App\Invoice;
use App\Delivery;
use App\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //línea necesaria

class Location extends Model
{
    use SoftDeletes; //Implementamos

	protected $fillable = ['name'];
    protected $dates = ['deleted_at']; //Registramos la nueva columna

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
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
