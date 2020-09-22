<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Stock;
use App\DeliveryDetail;

class StockDeliveryDetail extends Model
{
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function delivery_detail()
    {
        return $this->belongsTo(DeliveryDetail::class);
    }
}
