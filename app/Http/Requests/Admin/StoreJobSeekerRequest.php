<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobSeekerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:255'],
            'profile_photo' => ['nullable', 'string', 'max:255'],
            'headline' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'string', 'max:255'],
            'resume_path' => ['nullable', 'string', 'max:255'],
            'linkedin_url' => ['required', 'string', 'max:255'],
            'github_url' => ['required', 'string', 'max:255'],
            'website_url' => ['nullable', 'string', 'max:255'],
        ];
    }
}