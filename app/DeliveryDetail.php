<?php

namespace App;
use App\Location;
use App\Item;
use App\Delivery;
use App\StockDeliveryDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryDetail extends Model
{
    use SoftDeletes; //Implementamos

    protected $dates = ['deleted_at']; //Registramos la nueva columna
    
    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }


      public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function stock_deliveries()
    {
        return $this->belongsTo(StockDeliveryDetail::class);
    }
}
