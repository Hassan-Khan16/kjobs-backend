<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\PaginationRequest;
use Illuminate\Validation\Rule;

class ListPaginatedUserRequest extends PaginationRequest
{
    public function rules(): array
    {
        return [

            ...parent::rules(),

            'status' => [

                'nullable',

                Rule::in([
                    'active',
                    'inactive',
                ]),

            ],
            'role' => [
                'nullable',
                Rule::in([
                    'employer',
                    'user',
                ]),
            ],

        ];
    }
}