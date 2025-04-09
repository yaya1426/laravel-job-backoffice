<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobVacancyCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'type' => 'required|in:Full-time,Contract,Remote,Hybrid',
            'salary' => 'required|numeric|min:0',
            'companyId' => 'required|exists:companies,id',
            'categoryId' => 'required|exists:job_categories,id',
            'required_skills' => 'required|string',
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The job title is required.',
            'description.required' => 'Please provide a job description.',
            'location.required' => 'Location is required.',
            'type.required' => 'Job type must be selected.',
            'salary.required' => 'Salary is required.',
            'salary.numeric' => 'Salary must be a valid number.',
            'companyId.required' => 'A company must be associated with the job vacancy.',
            'companyId.exists' => 'The selected company is invalid.',
            'categoryId.required' => 'A job category must be selected.',
            'categoryId.exists' => 'The selected job category is invalid.',
            'required_skills.required' => 'Required skills must be specified.',
        ];
    }
}
