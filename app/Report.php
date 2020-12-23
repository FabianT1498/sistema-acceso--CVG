<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;


class Report extends Model
{
    //
    public function __construct(array $values = array(), array $attributes = array()){
        parent::__construct($attributes);

        $this->visit_id = isset($values['id']) ? $values['id'] : '';
        $this->visitor_fullname = isset($values['visitor_firstname']) && isset($values['visitor_lastname']) 
                ? $values['visitor_firstname'] . ' ' . $values['visitor_lastname'] 
                : '';
        $this->visitor_dni = isset($values['visitor_dni']) ? $values['visitor_dni'] : '';
        $this->auto_enrrolment = isset($values['auto_enrrolment']) ? $values['auto_enrrolment'] : '';
        $this->auto_model = isset($values['auto_model']) ? $values['auto_model'] : '';
        $this->auto_color = isset($values['auto_color']) ? $values['auto_color'] : '';
    }

    public $incrementing = true;

    protected $guarded = ['created_at', 'updated_at'];
}
