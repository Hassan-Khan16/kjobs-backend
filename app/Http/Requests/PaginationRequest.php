<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class PaginationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([

            'page' => $this->page ?? 1,

            'limit' => $this->limit ?? 10,

            'search' => filled($this->search)
                ? trim($this->search)
                : null,

        ]);
    }

    public function rules(): array
    {
        return [

            'page' => [
                'integer',
                'min:1',
            ],

            'limit' => [
                'integer',
                'min:1',
                'max:100',
            ],

            'search' => [
                'nullable',
                'string',
                'max:200',
            ],

        ];
    }
}