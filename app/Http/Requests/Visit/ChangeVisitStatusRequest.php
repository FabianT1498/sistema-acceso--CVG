<?php

namespace App\Http\Requests\Visit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Visit;

class ChangeVisitStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $auth_worker_id = $this->user()->worker_id;
        
        $visit = Visit::find($this->route('id'));

        return ($visit 
                && ($visit->status === 'POR CONFIRMAR')
                && ($visit->worker_id === $auth_worker_id));
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
