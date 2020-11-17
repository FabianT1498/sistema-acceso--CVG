<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Auto;

class EditAutoRequest extends FormRequest
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
                        || ($auto->user_id === $auth_user_id)));
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
