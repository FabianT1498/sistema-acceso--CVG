<?php

namespace App;
use App\Invoice;
use App\Inventory;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
