<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow request validation
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => "required|string|max:255|unique:companies,name,{$this->input('id')}",
            'address' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'website' => 'nullable|url',
            'owner_password' => 'nullable|string|min:8'
        ];
    }

    /**
     * Custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The company name is required.',
            'name.max' => 'The company name cannot exceed 255 characters.',
            'address.required' => 'The address is required.',
            'address.max' => 'The address cannot exceed 255 characters.',
            'industry.required' => 'The industry is required.',
            'industry.max' => 'The industry cannot exceed 255 characters.',
            'website.url' => 'The website URL must be valid.',
            'owner_password.min' => 'The password must be at least 8 characters.',
        ];
    }
}
