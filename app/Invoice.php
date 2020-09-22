<?php

namespace App;
use App\Company;
use App\State;
use App\Location;
use App\InvoiceDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Invoice extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at']; //Registramos la nueva columna
    public function state()
    {
	    return $this->belongsTo(State::class);
    }

    public function company()
    {
	    return $this->belongsTo(Company::class);
    }

    public function location()
    {
	    return $this->belongsTo(Location::class);
    }

    public function invoice_details()
    {
	    return $this->hasMany(InvoiceDetail::class);
    }
}
