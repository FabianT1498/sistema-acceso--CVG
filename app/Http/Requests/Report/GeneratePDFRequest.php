<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use DateTime;
use App\Visit;

class GeneratePDFRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $auth_user_role = Auth::user()->role_id;
      
        $columns = [
            'visits.status as status',
            'visits.date_attendance as date_attendance',
        ];

        $visit = Visit::select($columns)->where('visits.id', $this->route('id'))->first();
    
        if ($visit){
            $date_attendance = $visit->date_attendance;   
            $now = (new DateTime())->format('Y-m-d');
        }
    
        return ($visit && ($now <= $date_attendance) &&
                (($auth_user_role === 4 && $visit->status === "CONFIRMADA")
                    || ($visit->status === "COMPLETADA" && ($auth_user_role === 1 || $auth_user_role === 2 ))));
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
