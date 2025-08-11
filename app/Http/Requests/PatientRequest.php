<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PatientRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $patientId = $this->route('patient')?->id;
        return [
            'consultant_id'         => 'required|exists:consultants,id',
            'title_id'              => 'required|exists:drop_down_values,id',
            'first_name'            => 'required|string|max:255',
            'surname'               => 'required|string|max:255',
            'dob'                   => ['required', 'date', 'before:today'],
            'doctor_id'             => 'required|exists:doctors,id',
            'referral_doctor_id'    => 'nullable|exists:doctors,id',
            'other_doctor_id'       => 'nullable|exists:doctors,id',
            'solicitor_doctor_id'   => 'nullable|exists:doctors,id',
            'gender'                => 'required|in:Male,Female,Other',
            'email'                 => ['required','email:rfc,dns','max:255',
                                         Rule::unique('patients','email')->ignore($patientId)],
            'phone'                 => ['required', 'regex:/^(\+\d{1,3}[- ]?)?\d{7,15}$/'],
            'address'               => 'required|string|max:255',
            'emergency_contact'     => 'nullable|string|max:255',
            'medical_history'       => 'nullable|string',
            'insurance'             => 'nullable|string|max:255',
            'insurance_id'          => 'nullable|exists:insurances,id',
            'insurance_plan'        => 'nullable|string|max:255',
            'policy_no'             => 'nullable|string|max:255',
            'referral_reason'       => 'nullable|string',
            'symptoms'              => 'nullable|string',
            'patient_needs'         => 'nullable|string',
            'allergies'             => 'nullable|string',
            'diagnosis'             => 'nullable|string',
            'preferred_contact_id'  => 'nullable|exists:drop_down_values,id',
            'rip'                   => 'nullable|boolean',
            'rip_date'              => 'nullable|date',
            'sms_consent'           => 'nullable|boolean',
            'email_consent'         => 'nullable|boolean',
            'covid_19_vaccination_date' => 'nullable|date',
            'covid_19_vaccination_note' => 'nullable|string',
            'fully_covid_19_vaccinated' => 'nullable|boolean'
        ];
    }
}
