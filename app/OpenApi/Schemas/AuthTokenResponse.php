<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AuthTokenResponse',
    required: ['status', 'message', 'data'],
    properties: [
        new OA\Property(property: 'status', type: 'boolean', example: true),
        new OA\Property(property: 'message', type: 'string', example: 'Login successful'),
        new OA\Property(
            property: 'data',
            required: ['user', 'token', 'token_type'],
            properties: [
                new OA\Property(property: 'user', ref: '#/components/schemas/User'),
                new OA\Property(property: 'token', type: 'string'),
                new OA\Property(property: 'token_type', type: 'string', example: 'Bearer'),
            ],
            type: 'object',
        ),
    ],
)]
class AuthTokenResponse
{
}
