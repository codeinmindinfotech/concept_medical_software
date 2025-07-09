<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClinicRequest extends FormRequest
{
    public function rules(): array
    {
        $clinicId = $this->route('clinic')?->id;

        $rules = [
            'code'         => 'required|string|unique:clinics,code,' . $clinicId,
            'name'         => 'required|string|max:255',
            'address'      => 'nullable|string',
            'phone'        => 'nullable|string|max:20',
            'fax'          => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:255',
            'mrn'          => 'nullable|string|max:50',
            'planner_seq'  => 'nullable|string|max:50',
            'clinic_type'  => 'nullable|string|max:100',
        ];

        foreach (['mon','tue','wed','thu','fri','sat','sun'] as $day) {
            $rules[$day] = 'nullable|boolean';
            $rules["{$day}_start_am"] = 'nullable|date_format:H:i';
            $rules["{$day}_finish_am"] = 'nullable|date_format:H:i';
            $rules["{$day}_start_pm"] = 'nullable|date_format:H:i';
            $rules["{$day}_finish_pm"] = 'nullable|date_format:H:i';
            $rules["{$day}_interval"] = 'nullable|integer|min:1|max:240';
        }

        return $rules;
    }
}
