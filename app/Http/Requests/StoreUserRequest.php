<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $auth_user_role = $this->user()->role_id;
        $new_user_role = (int) $this->get('role_id');

        return ($auth_user_role === 1 || 
            (($new_user_role && $new_user_role > 2) && $auth_user_role === 2));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $worker_id = $this->worker_id;

        return [
            'worker_id' => [
                'required',
                'unique:users,worker_id'
            ],
            'worker_dni' => [
                'required',
                Rule::exists('workers', 'dni')->where(function ($query) use ($worker_id) {
                    $query->where('id', $worker_id);
                }),
                'max:10'
            ],
            'username' => [
                'required',
                'unique:users,username',
            ],
            /* 'email' => [
                'required',
                Rule::exists('workers', 'email')->where(function ($query) use ($worker_id) {
                    $query->where('id', $worker_id);
                }),
            ], */
            'password' => [
                'required',
                'min: 5'
            ],
            'role_id' => [
                'required',
                'exists:roles,id'
            ]
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
            'worker_dni.exists' => 'El trabajador ingresado no existe',
            'worker_dni.max' => 'La cedula del trabajador debe tener como maximo 10 caracteres',
            'username.unique' => 'El nombre de usuario debe ser unico',
            //'email.exists' => 'El correo no le corresponde al trabajador',
            'password.min' => 'La contraseña debe tener al menos 5 caracteres',
            'worker_id.unique' => 'Este trabajador ya posee un usuario'
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
            //'email' => strtolower($this->email),
        ];
        
        $this->merge($inputs);
    }
}
