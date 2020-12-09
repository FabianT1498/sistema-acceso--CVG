<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Report extends Pivot
{
    //
		public $incrementing = true;

		protected $guarded = ['created_at', 'updated_at'];
}
