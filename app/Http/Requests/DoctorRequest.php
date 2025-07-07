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
            'name'              => ['required', 'string', 'max:255'],
            'company'           => ['nullable', 'string', 'max:255'],
            'salutation'        => ['nullable', 'string', 'max:50'],
            'address'           => ['nullable', 'string', 'max:500'],
            'postcode'          => ['nullable', 'string', 'max:10'],
            'mobile'            => ['nullable', 'regex:/^(\+\d{1,3}[- ]?)?\d{7,15}$/'],
            'phone'             => ['nullable', 'regex:/^(\+\d{1,3}[- ]?)?\d{7,15}$/'],
            'fax'               => ['nullable', 'regex:/^(\+\d{1,3}[- ]?)?\d{7,15}$/'],
            'email'             => ['required', 'email:rfc,dns', 'max:255'],
            'contact'           => ['nullable', 'string', 'max:255'],
            'contact_type_id'   => ['nullable', 'exists:drop_down_values,id'],
            'payment_method_id' => ['nullable', 'exists:drop_down_values,id'],
            'note'             => ['nullable', 'string', 'max:1000'],
        ];
    }
    

}
