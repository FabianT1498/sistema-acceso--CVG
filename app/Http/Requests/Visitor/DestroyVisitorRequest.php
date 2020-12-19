<?php

namespace App\Http\Requests\Visitor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Visitor;

class DestroyVisitorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $auth_user_role = $this->user()->role_id;

        $visitor = Visitor::find($this->route('id'));

        return (($visitor && !$visitor->deleted_at) && $auth_user_role <= 2);
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
