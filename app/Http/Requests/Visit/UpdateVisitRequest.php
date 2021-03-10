<?php

namespace App\Http\Requests\Visit;

use Illuminate\Foundation\Http\FormRequest;
use DateTime;
use App\Visit;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class UpdateVisitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $auth_user_role = Auth::user()->role_id;
        $auth_worker_id = Auth::user()->worker_id;

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
                                || $auth_user_role === 4));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $today_date = new DateTime();
    
        $visitor_id  = isset($this->visitor_id) ? (int) $this->visitor_id : -1;
        $worker_id  = isset($this->worker_id) ? (int) $this->worker_id : -1;
        $auto_option = isset($this->auto_option) ? (int) $this->auto_option : 0;

        $rules = [
            'worker_dni' => [
                'required',
                Rule::exists('workers', 'dni')->where(function ($query) use ($worker_id) {
                    $query->where('id', $worker_id );
                }),
                'max:10'
            ],
            'attending_date' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:'. $today_date->format('Y-m-d')
            ],
            'entry_time' => [
                'required',
                'date_format:H:i',
            ],
            'departure_time' => [
                'required',
                'date_format:H:i',
                'after:entry_time'
            ],
            'building' => [
                'required'
            ],
            'department' => [
                'required'
            ],
            'issue' => [
                'required'
            ]
        ];

        if ($visitor_id === -1){

            $rules['visitor_firstname'] = array('required','max:50');
            $rules['visitor_lastname'] = array('required','max:50');
            $rules['visitor_dni'] = array(
                'required',
                'unique:visitors,dni',
                'max:10'
            );
            $rules['visitor_phone_number'] = array(
                'unique:visitors,phone_number',
                'max:15'
            );
            $rules['image'] = array(
                'image' ,
                'mimes:jpeg,png,jpg,gif',
                'max:512'
            );
            $rules['origin'] = array(
                'required'
            );

        } else {
            $rules['visitor_dni'] = array(
                'required',
                Rule::exists('visitors', 'dni')->where(function ($query) use ($visitor_id) {
                    $query->where('id', $visitor_id );
                }),
                'max:10',
            );
        }

        if ($auto_option){
        
            $auto_id = isset($this->auto_id) ? (int) $this->auto_id : -1;

            if ($auto_id === -1){
                
                $rules['auto_enrrolment'] = array(
                    'required',
                    'unique:autos,enrrolment',
                    'max:7'
                );

                $rules['auto_model'] = array(
                    'required',
                );
    
                $rules['auto_brand'] = array(
                    'required',
                );
    
                $rules['auto_color'] = array(
                    'required',
                );

            } else {
                $rules['auto_enrrolment'] = array(
                    'required',
                    Rule::exists('autos', 'enrrolment')->where(function ($query) use ($auto_id) {
                        $query->where('id', $auto_id );
                    }),
                    'max:7'
                );
            }
        
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        $visitor_id  = isset($this->visitor_id) ? (int) $this->visitor_id : -1;
        $auto_option = isset($this->auto_option) ? (int) $this->auto_option : 0;

        $messages = [
            'worker_dni.exists' => 'El trabajador ingresado no existe',
            'worker_dni.max' => 'La cedula del trabajador debe tener como maximo 10 caracteres',
            'visitor_dni.max' => 'La cedula del visitante debe tener como maximo 10 caracteres',
            'departure_time.after' => 'La hora de salida debe ser posterior a la hora de entrada',
            'building.required' => 'Debe indicar el edificio donde se realizara la visita',
            'department.required' => 'Debe indicar el departamento donde se realizara la visita',
            'attending_date.after_or_equal' => 'La fecha de la visita debe ser igual o posterior a la fecha de hoy',
            'issue.required' => 'Indique el asunto de la visita'
        ];

        if ($visitor_id === -1){
            $messages['visitor_dni.unique'] = 'La cedula del visitante ya fue registrada';
            $messages['visitor_phone_number.unique'] = 'El telefono del visitante ya fue registrado';
            $messages['origin.required'] = 'Es necesario que indique el origen del visitante';
            $messages['image.max'] = 'El peso maximo de la imagen es de 512 KB';
        } else {
            $messages['visitor_dni.exists'] = 'El visitante ingresado no existe, porfavor registrelo';
        }

        if ($auto_option){
            $auto_id = isset($this->auto_id) ? (int) $this->auto_id : -1;

            if ($auto_id === -1){
                $messages['auto_enrrolment.unique'] = 'La matricula de este auto ya fue registrada';
            } else {
                $messages['auto_enrrolment.exists'] = 'El auto ingresado no existe';
            }
        }

        return $messages;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $auto_option = isset($this->auto_option) ? (int) $this->auto_option : 0;

        $inputs = [
            'worker_dni' => strtoupper($this->worker_dni),
            'visitor_dni' => strtoupper($this->visitor_dni),
            'attending_date' => date('Y-m-d', strtotime($this->attending_date)),
            'entry_time' => date('H:i', strtotime($this->entry_time)),
            'departure_time' => date('H:i', strtotime($this->departure_time)),
            'building' => strtoupper($this->building),
            'department' => strtoupper($this->department),
        ];

        if ($auto_option){
            $inputs['auto_enrrolment'] = strtoupper($this->auto_enrrolment);
            $inputs['auto_model'] = strtoupper($this->auto_model);
            $inputs['auto_brand'] = strtoupper($this->auto_brand);
        }   

        $this->merge($inputs);
    }
}
