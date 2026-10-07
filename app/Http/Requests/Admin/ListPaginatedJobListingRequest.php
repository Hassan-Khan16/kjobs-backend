<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\PaginationRequest;
use Illuminate\Validation\Rule;

class ListPaginatedJobListingRequest extends PaginationRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'status' => ['nullable', Rule::in(['draft', 'open', 'closed'])],
        ];
    }
}