<?php

namespace App;

use App\Department;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    //
    public $timestamps = false;

    //
    public function departments(){
        return $this->hasMany(Department::class);
    }
}
