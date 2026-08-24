<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ApiErrorResponse',
    required: ['status', 'message'],
    properties: [
        new OA\Property(property: 'status', type: 'boolean', example: false),
        new OA\Property(property: 'message', type: 'string', example: 'User not found.'),
        new OA\Property(property: 'errors', nullable: true),
    ],
)]
class ApiErrorResponse
{
}
