<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDelegateDraftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            // Personal info
            'first_name' => ['required', 'regex:/^[a-zA-Z\s]+$/', 'max:100'],
            'last_name'  => ['nullable', 'regex:/^[a-zA-Z\s]+$/', 'max:100'],
            'email' => ['required', 'email:rfc,dns', 'max:255'],
            'phone' => ['required', 'regex:/^\+?[0-9]{8,15}$/'],
            'country_code' => ['required', 'numeric'],
            'dob' => ['required', 'date', 'before:-18 years'],
            'age' => ['required', 'integer', 'min:18'],
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],

            // Identity
            'id_type' => ['required', Rule::in(['VOTER', 'AADHAR', 'DL', 'PASSPORT'])],
            'id_number' => ['required', 'max:30'],

            // Address
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'regex:/^[a-zA-Z\s]+$/', 'max:100'],
            'pincode' => ['required', 'regex:/^[0-9]{4,10}$/'],
            'country' => ['required', 'size:2'],

            // Registration meta
            'category' => [
                'required',
                Rule::in(['Delegate', 'Film Fraternity', 'Senior Citizen', 'Student'])
            ],
            'pickup_location' => ['required', Rule::in(['KCA', 'SCA', 'ORI'])],

            // Documents
            'id_document' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],

            // Declaration
            'is_agree' => ['required', 'accepted'],
        ];
    }

    /**
     * Custom error messages for the defined rules.
     */
    public function messages(): array
    {
        return [
            'first_name.regex' => 'The first name should only contain letters and spaces.',
            'last_name.regex' => 'The last name should only contain letters and spaces.',
            'dob.before' => 'You must be at least 18 years old to register.',
            'email.email' => 'Please provide a valid professional email address.',
            'phone.regex' => 'Please enter a valid phone number (8 to 15 digits).',
            'id_document.max' => 'The ID document size must not exceed 2MB.',
            'photo.max' => 'The passport photo size must not exceed 2MB.',
            'is_agree.accepted' => 'You must agree to the declaration to proceed.',
            'country.size' => 'Please enter a valid 2-character country code (e.g., IN).',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $type = $this->input('id_type');
            $value = strtoupper($this->input('id_number'));

            $patterns = [
                'VOTER'    => '/^[A-Z]{3}[0-9]{7}$/',
                'AADHAR'   => '/^[0-9]{12}$/',
                'PASSPORT' => '/^[A-Z][0-9]{7}$/',
                'DL'       => '/^[A-Z0-9]{5,20}$/',
            ];

            if (isset($patterns[$type]) && !preg_match($patterns[$type], $value)) {
                $validator->errors()->add(
                    'id_number',
                    'ID number format does not match ' . ucfirst(strtolower($type)) . ' requirements.'
                );
            }
        });
    }
}