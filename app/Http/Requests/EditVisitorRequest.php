<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Visitor;

class EditVisitorRequest extends FormRequest
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
        return [
            //
        ];
    }
}
