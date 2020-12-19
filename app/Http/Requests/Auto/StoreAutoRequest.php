<?php

namespace App\Http\Requests\Auto;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Visitor;

class StoreAutoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $auth_user_role = $this->user()->role_id;
     
        return ($auth_user_role === 4);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $visitor_id = $this->visitor_id;

        $rules = [
            'auto_enrrolment' => [
                'required',
                'unique:autos,enrrolment',
                'max:7'
            ],
            'auto_color' => ['required'],
            'auto_brand' => ['required'],
            'auto_model' => ['required'],
        ];

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {

        $messages = [
            'auto_enrrolment.unique' => 'La matricula de este auto ya fue registrada'
        ];

        return $messages;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
 
        $inputs['auto_enrrolment'] = strtoupper($this->auto_enrrolment);
        $inputs['auto_model'] = strtoupper($this->auto_model);
        $inputs['auto_brand'] = strtoupper($this->auto_brand);
        
        $this->merge($inputs);
    }
}
