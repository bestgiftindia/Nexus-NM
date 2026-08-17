<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RelationshipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'dob' => ['required', 'date', 'before:today'],
            'tob' => ['nullable', 'date_format:H:i'],
            'mobile_number' => ['nullable', 'numeric', 'digits:10'],
            'gender' => ['required', 'in:1,2,3'],
            'email' => ['nullable', 'email', 'max:100'],
            'location' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function attributes()
    {
        return [
            'dob' => 'date of birth',
            'tob' => 'time of birth',
            'email' => 'email id'
        ];
    }
}
