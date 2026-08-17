<?php

namespace App\Http\Requests\Loshugrid;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LoshugridRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('loshugrid-create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100', 'regex:/^[A-Za-z\s]+$/'],
            'middle_name'  => 'nullable|string|max:100',
            'last_name'    => 'nullable|string|max:100',
            'date_of_birth' => 'required|date',
            'gender'       => 'required|in:1,2,3',
            'email'        => 'nullable|email|max:255',
            'phone_code'   => 'required|string|max:10',
            'phone'        => 'nullable|digits_between:8,15',
        ];
    }
}
