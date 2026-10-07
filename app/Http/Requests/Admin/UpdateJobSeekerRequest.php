<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJobSeekerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('id');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'phone' => ['sometimes', 'nullable', 'string', 'max:255'],
            'profile_photo' => ['sometimes', 'nullable', 'string', 'max:255'],
            'headline' => ['sometimes', 'required', 'string', 'max:255'],
            'bio' => ['sometimes', 'nullable', 'string'],
            'location' => ['sometimes', 'required', 'string', 'max:255'],
            'date_of_birth' => ['sometimes', 'required', 'date'],
            'gender' => ['sometimes', 'required', 'string', 'max:255'],
            'resume_path' => ['sometimes', 'nullable', 'string', 'max:255'],
            'linkedin_url' => ['sometimes', 'required', 'string', 'max:255'],
            'github_url' => ['sometimes', 'required', 'string', 'max:255'],
            'website_url' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}