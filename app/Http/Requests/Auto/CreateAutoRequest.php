<?php

namespace App\Http\Requests\Auto;

use Illuminate\Foundation\Http\FormRequest;
use App\Auto;

class CreateAutoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $auth_user_role = $this->user()->role_id;
        
        return $auth_user_role === 4 || $auth_user_role === 5;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
