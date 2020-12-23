<?php

namespace App\Http\Requests\Visitor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVisitorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (true);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'visitor_firstname' => ['required','max:50'],
            'visitor_lastname' => ['required','max:50'],
            'visitor_dni' => [
                'required',
                'unique:visitors,dni',
                'max:10'
            ],
            'visitor_phone_number' => [
                'unique:visitors,phone_number',
                'max:15'
            ],
            'image' => [
                'image' ,
                'mimes:jpeg,png,jpg,gif',
                'max:512'
            ]
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
