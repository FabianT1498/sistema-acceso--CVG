<?php

namespace App\Http\Requests;

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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
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
                Rule::unique('visitors', 'dni')->where(function ($query) {
                    return $query->where('deleted_at', NULL);
                }),
                'max:10'
            ],
            'phone_number' => [
                'required',
                Rule::unique('visitors', 'phone_number')->where(function ($query) {
                    return $query->where('deleted_at', NULL);
                }),
                'max:15'
            ],
            'image' => [
                'required',
                'image' ,
                'mimes:jpeg,png,jpg,gif',
                'max:2048'
            ],
        ];
    }
}
