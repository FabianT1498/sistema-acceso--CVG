<?php

namespace App;
use App\Invoice;
use App\InventoryDetail;
use App\Item;
use App\Stock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceDetail extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at']; //Registramos la nueva columna
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function inventory_details()
    {
        return $this->hasMany(InventoryDetail::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}
