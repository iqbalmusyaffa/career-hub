<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('HR') || $this->user()->hasRole('Super Admin') || $this->user()->hasRole('Company Owner');
    }

    protected function prepareForValidation(): void
    {
        if (is_array($this->benefits)) {
            $this->merge([
                'benefits' => implode(', ', array_filter($this->benefits)),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'division' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'google_maps_link' => 'nullable|string',
            'work_type' => 'required|string|max:255',
            'salary' => 'nullable|string|max:255',
            'quota' => 'nullable|integer|min:1',
            'batch' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'benefits' => 'nullable|string',
            'deadline' => 'required|date',
            'status' => 'required|string|in:active,inactive,closed,draft',
            'experience_level' => 'nullable|string|max:255',
            'education_level' => 'nullable|string|max:255',
            'major_requirement' => 'nullable|string|max:255',
            'skills_required' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'gender_requirement' => 'nullable|string|max:255',
            'age_range' => 'nullable|string|max:255',
        ];
    }
}
