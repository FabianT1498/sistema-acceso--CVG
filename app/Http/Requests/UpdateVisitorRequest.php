<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Visitor;

class UpdateVisitorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $auth_user_role = $this->user()->role_id;

        $auth_user_id = null;

        if( $auth_user_role === 3 ){
            $auth_user_id = $this->user()->id;
        }
        
        $visitor = Visitor::find($this->route('visitante'));

        return (($visitor && !$visitor->deleted_at) 
            && ($auth_user_role !== 3 || ($visitor->user_id === $auth_user_id)));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $visitor_id = $this->route('visitante');

        return [
            'visitor_firstname' => [
                'bail',
                'required',
                'max:50',
            ],    
            'visitor_lastname' => [
                'required',
                'max:50', 
            ],
            'visitor_dni' => [                
                'required',
                Rule::unique('visitors', 'dni')
                    ->ignore($visitor_id),
                'max:10'
            ],
            'visitor_phone_number' => [
                'required',
                Rule::unique('visitors', 'phone_number')
                    ->ignore($visitor_id),
                'max:15'
            ],
            'image' => [
                'image' ,
                'mimes:jpeg,png,jpg,gif',
                'max:512'
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
       
        $messages = [
            'visitor_dni.unique' => 'La cedula del visitante ya fue registrada',
            'visitor_phone_number.unique' => 'El telefono del visitante ya fue registrado',
            'image.required' => 'Es necesario que suba la imagen del visitante',
            'image.max' =>'El peso maximo de la imagen es de 512 KB'
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
        
        $inputs = [
            'visitor_dni' => strtoupper($this->visitor_dni),
        ];

        $this->merge($inputs);
    }
}

