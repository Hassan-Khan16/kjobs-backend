<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $employerId = $this->route('id');

        return [
            'email' => ['sometimes', 'email', 'unique:users,email,' . $employerId],
            'company_name' => ['sometimes', 'string', 'max:255'],
            'contact_person_name' => ['sometimes', 'string', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:20'],
            'company_description' => ['sometimes', 'nullable', 'string'],
            'website' => ['sometimes', 'nullable', 'url', 'max:255'],
            'logo' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}
