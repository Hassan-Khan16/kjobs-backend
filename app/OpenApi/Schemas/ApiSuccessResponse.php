<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ApiSuccessResponse',
    required: ['status', 'message'],
    properties: [
        new OA\Property(property: 'status', type: 'boolean', example: true),
        new OA\Property(property: 'message', type: 'string', example: 'Success'),
        new OA\Property(property: 'data', nullable: true),
    ],
)]
class ApiSuccessResponse
{
}
