<?php

namespace App;
use App\Group;
use App\Type;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //línea necesaria

class SubGroup extends Model
{
    use SoftDeletes; //Implementamos 

	protected $fillable = ['name'];
    protected $dates = ['deleted_at']; //Registramos la nueva columna

    public function group()
    {
	    return $this->belongsTo(Group::class)->withTrashed();
    }

    public function types()
    {
	    return $this->hasMany(Type::class)->withTrashed();
    }
}
