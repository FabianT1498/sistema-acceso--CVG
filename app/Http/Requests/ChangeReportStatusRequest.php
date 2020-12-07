<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Report;

class ChangeReportStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $auth_worker_id = $this->user()->worker_id;
        
        $report = Report::find($this->route('id'));

        return (($report && !$report->deleted_at)
                && ($report->status === 'POR CONFIRMAR')
                && ($report->worker_id === $auth_worker_id));
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
