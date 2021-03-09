<?php

namespace App\Http\Requests\Auto;

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
        
        $auto = Auto::find($this->route('auto'));

        return ($auto 
                && ($auth_user_role === 4 || $auth_user_role === 5));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $auto_id = $this->route('auto');
    
        $rules = [       
            'auto_enrrolment' => [
                'required',
                Rule::unique('autos', 'enrrolment')
                    ->ignore($auto_id),
                'max:7'
            ],
            'auto_model' => ['required'],
            'auto_brand' => ['required'],
            'auto_color' => ['required'],
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
            'auto_enrrolment.unique' => 'La placa de este auto ya ha sido registrada',
            'auto_color.required' => 'Indique el color del auto por favor',
            'auto_model.required' => 'Indique el modelo del auto por favor',
            'auto_brand.required' => 'Indique la marca del auto por favor',
        ];

        return $messages;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     **/
    protected function prepareForValidation()
    {
 
        $inputs['auto_enrrolment'] = strtoupper($this->auto_enrrolment);
        $inputs['auto_model'] = strtoupper($this->auto_model);
        $inputs['auto_brand'] = strtoupper($this->auto_brand);
        
        $this->merge($inputs);
    }
}
