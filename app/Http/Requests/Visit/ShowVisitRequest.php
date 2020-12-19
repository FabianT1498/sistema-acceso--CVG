<?php

namespace App\Http\Requests\Visit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Visit;

class ShowVisitRequest extends FormRequest
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
        $auth_worker_id = $this->user()->worker_id;
        
        $visit = Visit::find($this->route('visita'));

        return (($visit && !$visit->deleted_at) 
                && ($auth_user_role !== 3 
                        || $visit->worker_id === $auth_worker_id));
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
