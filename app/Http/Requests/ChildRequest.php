<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChildRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100','regex:/^[a-zA-Z0-9\s]+$/'],
            'middle_name' => ['nullable', 'string', 'max:100','regex:/^[a-zA-Z0-9\s]+$/'],
            'last_name' => ['nullable', 'string', 'max:100','regex:/^[a-zA-Z0-9\s]+$/'],
            'dob' => ['required', 'date', 'before:today'],
            'mobile_number' => ['nullable', 'numeric', 'digits:10'],
            'gender' => ['required', 'in:1,2,3'],
            'email' => ['nullable', 'email', 'max:100'],
            'guardian_name' => ['nullable', 'string', 'max:100','regex:/^[a-zA-Z0-9\s]+$/'],
            'guardian_relation' => ['nullable', 'string', 'max:100','regex:/^[a-zA-Z0-9\s]+$/'],
            'birth_location' => ['nullable', 'string', 'max:255'],
            'time_of_birth' => ['nullable', 'date_format:H:i'],
        ];
    }

    public function messages()
{
    return [
        'first_name.regex' => 'First name can only contain letters, numbers, and spaces.',
        'middle_name.regex' => 'Middle name can only contain letters, numbers, and spaces.',
        'last_name.regex' => 'Last name can only contain letters, numbers, and spaces.',
        'guardian_name.regex' => 'Guardian name can only contain letters, numbers, and spaces.',
        'guardian_relation.regex' => 'Guardian relation can only contain letters, numbers, and spaces.',
    ];
}

    function attributes()
    {
        return [
            'dob' => 'date of birth',
            'email' => 'guardian email',
            'mobile_number' => 'guardian mobile number'
        ];
    }
}
