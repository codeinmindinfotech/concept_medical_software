<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConsultantRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $consultantId = $this->consultant->id ?? null;

        return [
            'code'    => ['required', 'string', 'max:50',Rule::unique('consultants', 'code')->ignore($consultantId)],
            'name'    => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'phone'   => ['required', 'string', 'max:20', 'regex:/^(\+\d{1,3}[- ]?)?\d{7,15}$/'],
            'fax'     => ['nullable', 'string', 'max:20', 'regex:/^(\+\d{1,3}[- ]?)?\d{7,15}$/'],
            'email'   => ['required', 'email:rfc,dns', 'max:255',
                        Rule::unique('consultants', 'email')->ignore($consultantId)],
            'imc_no'  => ['required', 'string', 'max:50',
                        Rule::unique('consultants', 'imc_no')->ignore($consultantId)],
            'image'   => ['nullable', 'image', 'max:2048'], // max 2MB
            'insurance_id'   => ['required', 'array', 'min:1'],
            'insurance_id.*' => ['integer', 'exists:insurances,id'],
        ];
    }
}
