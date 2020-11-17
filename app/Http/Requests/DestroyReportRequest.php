<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Report;

class DestroyReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $auth_user_role = $this->user()->role_id;

        $report = Report::find($this->route('id'));

        return (($report && !$report->deleted_at) && $auth_user_role <= 2);
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
