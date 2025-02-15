<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow validation
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'bail|required|string|max:255|unique:companies,name',
            'address' => 'bail|required|string|max:255',
            'industry' => 'bail|required|string|max:255',
            'website' => 'bail|nullable|url|max:255',

            // Owner Fields
            'owner_name' => 'bail|required|string|max:255',
            'owner_email' => 'bail|required|email|max:255|unique:users,email',
            'owner_password' => 'bail|required|string|min:8',
        ];
    }

    /**
     * Custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The company name is required.',
            'name.unique' => 'This company name is already taken.',
            'address.required' => 'The address is required.',
            'industry.required' => 'The industry is required.',
            'website.url' => 'The website URL must be a valid format.',

            // Owner validation messages
            'owner_name.required' => 'The owner name is required.',
            'owner_email.required' => 'The owner email is required.',
            'owner_email.email' => 'Please enter a valid email address.',
            'owner_email.unique' => 'This email is already registered.',
            'owner_password.required' => 'The owner password is required.',
            'owner_password.min' => 'The password must be at least 8 characters.',
        ];
    }
}
