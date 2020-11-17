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
            'firstname' => [
                'bail',
                'required',
                'max:50',
            ],    
            'lastname' => [
                'required',
                'max:50', 
            ],
            'dni' => [                
                'required',
                Rule::unique('visitors', 'dni')
                    ->ignore($visitor_id)
                    ->where(function ($query) {
                        return $query->where('deleted_at', NULL);
                    }),
                'max:10'
            ],
            'phone_number' => [
                'required',
                Rule::unique('visitors', 'phone_number')
                    ->ignore($visitor_id)          
                    ->where(function ($query) {
                        return $query->where('deleted_at', NULL);
                    }),
                'max:15'
            ],
            'image' => [
                'image' ,
                'mimes:jpeg,png,jpg,gif',
                'max:2048'
            ],
        ];
    }
}
