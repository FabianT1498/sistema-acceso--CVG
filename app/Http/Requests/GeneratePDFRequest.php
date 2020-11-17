<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Report;

class GeneratePDFRequest extends FormRequest
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

        $report = Report::find($this->route('id'));
     
        return (($report && !$report->deleted_at) 
                && ($auth_user_role !== 3 
                        || ($report->user_id === $auth_user_id) 
                                || ($report->worker_id === $auth_worker_id)));
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
