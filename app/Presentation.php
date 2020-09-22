<?php

namespace App;
use App\Item;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //línea necesaria


class Presentation extends Model
{
    use SoftDeletes; //Implementamos 

	protected $fillable = ['name'];
    protected $dates = ['deleted_at']; //Registramos la nueva columna
    
    public function items()
    {
        return $this->hasMany(Item::class)->withTrashed();
    }
}
