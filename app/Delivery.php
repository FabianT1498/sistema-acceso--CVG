<?php

namespace App;
use App\Company;
use App\Location;
use App\DeliveryDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Delivery extends Model
{
    use SoftDeletes; //Implementamos

    protected $dates = ['deleted_at']; //Registramos la nueva columna

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function delivery_details()
    {
        return $this->hasMany(DeliveryDetail::class);
    }
}
