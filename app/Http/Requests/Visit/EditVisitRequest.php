<?php

namespace App\Http\Requests\Visit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

use App\Visit;

class EditVisitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $auth_worker_id = $this->user()->worker_id;
        $auth_role_id = $this->user()->role_id;

        $columns = [
            'visits.status',
            'visits.worker_id',
            'report_visit.id as report_id'
        ];

        $visit = Visit::select($columns)->where('visits.id', $this->route('visita'))
            ->leftJoin(DB::raw("(SELECT DISTINCT ON (visit_id) * FROM reports) as report_visit"),
                function($join) {
                    $join->on("report_visit.visit_id", "=", "visits.id");
                }
            )->first();
        
        return ($visit && $visit->status !== "CANCELADA"
                && is_null($visit->report_id)
                        && ($visit->worker_id === $auth_worker_id
                                || $auth_role_id === 4));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }
}
