<?php

namespace App;
use App\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //línea necesaria

class Role extends Model
{
    use SoftDeletes; 
    protected $dates = ['deleted_at']; //Registramos la nueva columna
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
