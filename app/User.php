<?php

namespace App;

use App\Role;
use App\Report;
use App\PassRecord;
use App\Worker;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes; //línea necesaria
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id', 'password',
    ];
    protected $dates = ['deleted_at']; //Registramos la nueva columna

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    /**
     * The passes that belong to the user.
     */
    public function passesIssued()
    {
        return $this->belongsToMany(Report::class, 'pass_record')
            ->withTimestamps()
            ->using(PassRecord::class)
            ->withPivot([
                'created_at',
                'updated_at',
            ]);
    }

    public function reports(){
        return $this->hasMany(Report::class);
    }

}
