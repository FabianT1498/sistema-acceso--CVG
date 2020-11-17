<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Report;
use Illuminate\Validation\Rule;


class UpdateReportRequest extends FormRequest
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

        $report = Report::find($this->route('reporte'));
     
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
        $visitor_id  =  $this->get('visitor_id');
        $worker_id = $this->get('worker_id');

        $rules = [
            'visitor_id' => ['bail', 'required', 'exists:visitors,id'],
            'visitor_dni' => [
                'required',
                Rule::exists('visitors', 'dni')->where(function ($query) use ($visitor_id) {
                    $query->where('id', $visitor_id );
                }),
                'max:10'
            ],
            'worker_id' => ['required', 'exists:workers,id'],
            'worker_dni' => [
                'required',
                Rule::exists('workers', 'dni')->where(function ($query) use ($worker_id) {
                    $query->where('id', $worker_id);
                }),
                'max:10'
            ],
            'attending_date' => [
                'required',
                'date_format:Y-m-d H:i',
            ]
        ];

        $auto_id = (int) $this->get('auto_id');

        if (isset($auto_id) && $auto_id >= 0){
            $rules['auto_id'] = array(
                Rule::exists('autos', 'id')->where(function ($query) use ($visitor_id) {
                    $query->where('visitor_id', $visitor_id);
                })       
            );
        }

        return $rules;
    }
}
