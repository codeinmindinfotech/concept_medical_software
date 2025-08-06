<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClinicRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        foreach (['mon','tue','wed','thu','fri','sat','sun'] as $day) {
            foreach (['start_am','finish_am','start_pm','finish_pm'] as $suffix) {
                $field = "{$day}_{$suffix}";
                if ($this->filled($field)) {
                    $this->merge([
                        $field => date('H:i:s', strtotime($this->{$field})),
                    ]);
                }
            }
        }

        if (!$this->filled('color')) {
            $this->merge([
                'color' => '#3788d8',
            ]);
        }
    }

    public function rules(): array
    {
        $clinicId = $this->route('clinic')?->id;
        $rules = [
            'code'         => 'required|string|unique:clinics,code,' . $clinicId,
            'name'         => 'required|string|max:255',
            'address'      => 'nullable|string',
            'phone'        => ['nullable', 'regex:/^(\+\d{1,3}[- ]?)?\d{7,15}$/'],
            'fax'          => 'nullable|string|max:20',
            'email'        => ['required','email:rfc,dns','max:255',
                                Rule::unique('clinics','email')->ignore($clinicId)],
            'mrn'          => 'nullable|string|max:50',
            'planner_seq'  => 'nullable|string|max:50',
            'clinic_type'  => 'required|in:clinic,hospital',
            'color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6})$/'],
        ];

        foreach (['mon','tue','wed','thu','fri','sat','sun'] as $day) {
            $rules[$day] = 'nullable|boolean';
            $rules["{$day}_start_am"] = 'nullable|date_format:H:i:s';
            $rules["{$day}_finish_am"] = 'nullable|date_format:H:i:s|after:'.$day.'_start_am';
            $rules["{$day}_start_pm"] = 'nullable|date_format:H:i:s';
            $rules["{$day}_finish_pm"] = 'nullable|date_format:H:i:s|after:'.$day.'_start_pm';
            $rules["{$day}_interval"] = 'nullable|integer|min:1|max:240';
        }

        return $rules;
    }
}
