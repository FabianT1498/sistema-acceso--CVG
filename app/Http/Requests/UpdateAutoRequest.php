<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Auto;

class UpdateAutoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $auth_user_role = $this->user()->role_id;
        $auth_user_id = $this->user()->id;
        
        $auto = Auto::find($this->route('auto'));

        return (($auto && !$auto->deleted_at) 
                && ($auth_user_role !== 3 
                        || ($auto->user_id === $auth_user_id 
                                && $auto->visitor->user_id === $auth_user_id)));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $auto_id = $this->route('auto');
        $visitor_id = $this->visitor_id;

        $rules = [
            'visitor_id' => ['bail', 'required', 'exists:visitors,id'],
            'visitor_dni' => [
                'required',
                Rule::exists('visitors', 'dni')->where(function ($query) use ($visitor_id) {
                    $query->where('id', $visitor_id );
                }),
                'max:10'
            ],
            'auto_enrrolment' => [
                'required',
                Rule::unique('autos', 'enrrolment')
                    ->ignore($auto_id)
                    ->where(function ($query) {
                        return $query->where('deleted_at', NULL);
                    }),
                'max:7'
            ],
            'auto_color' => ['required']
        ];

        $auto_brand_chk = (int) $this->check_auto_brand;
        $auto_model_chk = (int) $this->check_auto_model;
        
        if (isset($auto_brand_chk) && $auto_brand_chk === 1){
            $rules['auto_brand_input'] = array('required', 'unique:auto_brands,name', 'min:3');
            $rules['auto_model_input'] = array('required', 'unique:auto_models,name', 'min:3');
        } else if (isset($auto_model_chk) && $auto_model_chk === 1){
            $rules['auto_brand_select'] = array('required', 'exists:auto_brands,id');
            $rules['auto_model_input'] = array('required', 'unique:auto_models,name', 'min:3');
        } else {
            $rules['auto_brand_select'] = array('required', 'exists:auto_brands,id');
            $rules['auto_model_select'] = array('required', 'exists:auto_models,id');
        }

        return $rules;
    }
}
