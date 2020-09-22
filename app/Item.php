<?php

namespace App;
use App\InvoiceDetail;
use App\DeliveryDetail;
use App\Type;
use App\Presentation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use SoftDeletes; //Implementamos

    protected $dates = ['deleted_at']; //Registramos la nueva columna
    
    public function invoice_details()
    {
        return $this->hasMany(InvoiceDetail::class)->withTrashed();
    }
    public function delivery_details()
    {
        return $this->hasMany(DeliveryDetail::class)->withTrashed();
    }

    public function type()
    {
        return $this->belongsTo(Type::class)->withTrashed();
    }
    
    public function presentation()
    {
        return $this->belongsTo(Presentation::class)->withTrashed();
    }
}
