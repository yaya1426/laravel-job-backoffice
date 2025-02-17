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
            'type' => 'required|in:full-time,part-time,remote',
            'salary' => 'required|numeric|min:0',
            'companyId' => 'required',
            'categoryId' => 'required',
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
            'categoryId.required' => 'A job category must be selected.',
        ];
    }
}
