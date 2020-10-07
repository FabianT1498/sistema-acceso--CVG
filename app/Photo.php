<?php

namespace App;

use App\Visitor;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    //
		protected $fillable = ['path'];

		/**
     * Get the user that owns the phone.
     */
    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

}
