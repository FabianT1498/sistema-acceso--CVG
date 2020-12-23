<?php

namespace App\Http\Requests\Visit;

use Illuminate\Foundation\Http\FormRequest;

use App\Visit;

class DestroyVisitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $auth_user_role = $this->user()->role_id;

        $visit = Visit::find($this->route('id'));

        return (($visit && !$visit->deleted_at) && $auth_user_role <= 2);
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
