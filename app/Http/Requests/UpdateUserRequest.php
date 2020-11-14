<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\User;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $auth_user_role = $this->user()->role_id;
        $new_user_role = (int) $this->role_id;

        $user = User::find($this->route('usuario'));

        return ($user && !$user->deleted_at && ($auth_user_role === 1 || 
            (($new_user_role && $new_user_role > 2) && $auth_user_role === 2)));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user_id = $this->route('usuario');

        $rules = [
            'username' => [
                'required',
                Rule::unique('users', 'username')
                    ->ignore($user_id)
                    ->where(function ($query) {
                        return $query->where('deleted_at', NULL);
                    })
            ],
            'role_id' => [
                'required',
                'exists:roles,id'
            ],
        ];

        if ($this->password && $this->password !== ''){
            $rules['password'] = array('required', 'min:9');
        }

        return $rules;
    }
}
