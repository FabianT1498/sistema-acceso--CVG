<?php

namespace App;
use App\Item;
use App\Inventory;
use App\InvoiceDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryDetail extends Model
{
    use SoftDeletes; //Implementamos

    protected $dates = ['deleted_at']; //Registramos la nueva columna
    
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function invoice_detail()
    {
        return $this->belongsTo(InvoiceDetail::class);
    }
}
