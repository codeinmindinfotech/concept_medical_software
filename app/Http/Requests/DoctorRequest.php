<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|max:20',
            'email'    => 'nullable|email|max:255',
            'address'  => 'required|string',
            'postcode' => 'nullable|string|max:10',
            'gender'   => 'required|in:Male,Female,Other',
            'note'     => 'nullable|string',
        ];
    }
}
