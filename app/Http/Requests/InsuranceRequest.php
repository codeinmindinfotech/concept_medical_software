<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsuranceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code'                  => 'required|string|max:50|unique:insurances,code,' . ($this->insurance->id ?? 'NULL') . ',id',
            'address'               => 'required|string|max:255',
            'contact_name'          => 'required|string|max:255',
            'email'                 => ['required', 'email:rfc', 'max:255'],
            'contact'               => ['required', 'regex:/^(\+\d{1,3}[- ]?)?\d{7,15}$/'],
            'postcode'              => 'required|string|max:10',
            'fax'                   => ['nullable', 'regex:/^(\+\d{1,3}[- ]?)?\d{7,15}$/'],
        ];
    }
}
