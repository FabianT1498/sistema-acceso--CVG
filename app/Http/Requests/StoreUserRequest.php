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
                'bail',
                'required',
                'exists:workers,id',
                Rule::unique('users', 'worker_id')->where(function ($query) {
                    return $query->where('deleted_at', NULL);
                })
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
                Rule::unique('users', 'username')->where(function ($query) {
                    return $query->where('deleted_at', NULL);
                })
            ],
            'email' => [
                'required',
                Rule::exists('workers', 'email')->where(function ($query) use ($worker_id) {
                    $query->where('id', $worker_id);
                }),
            ],
            'password' => [
                'required',
                'min: 9'
            ],
            'role_id' => [
                'required',
                'exists:roles,id'
            ]
        ];
    }
}
