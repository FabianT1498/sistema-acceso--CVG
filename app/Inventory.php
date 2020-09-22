<?php

namespace App;
use App\State;
use App\Location;
use App\InventoryDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use SoftDeletes; //Implementamos

    protected $dates = ['deleted_at']; //Registramos la nueva columna
    
    public function inventory_details()
    {
        return $this->hasMany(InventoryDetail::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
