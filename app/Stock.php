<?php

namespace App;
use App\InvoiceDetail;
use App\StockDeliveryDetail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use SoftDeletes; //Implementamos

    protected $dates = ['deleted_at']; //Registramos la nueva columna
    
    public function invoice_detail()
    {
        return $this->belongsTo(InvoiceDetail::class);
    }
    public function stock_deliveries()
    {
        return $this->belongsTo(StockDeliveryDetail::class);
    }
}
