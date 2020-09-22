<?php

namespace App;
use App\SubGroup;
use App\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //línea necesaria

class Type extends Model
{
    use SoftDeletes; //Implementamos 

	protected $fillable = ['name'];
    protected $dates = ['deleted_at']; //Registramos la nueva columna

    public function sub_group()
    {
	    return $this->belongsTo(SubGroup::class)->withTrashed();
    }

    public function items()
    {
	    return $this->hasMany(Item::class)->withTrashed();
    }
}
