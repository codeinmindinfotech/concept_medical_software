<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'dob'               => 'required|date',
            'doctor_id'         => 'exists:doctors,id',
            'gender'            => 'required|in:Male,Female,Other',
            'phone'             => 'required|string|max:20',
            'email'             => 'nullable|email|max:255',
            'address'           => 'required|string',
            'emergency_contact' => 'nullable|string|max:255',
            'medical_history'   => 'nullable|string',
            'insurance'         => 'nullable|string|max:255',
        ];
    }
}
