<?php

namespace App\OpenApi\Operations\Auth;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/user',
    summary: 'Get the authenticated user',
    tags: ['Auth'],
    security: [['sanctum' => []]],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Authenticated user',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'User retrieved successfully'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/User'),
                ],
            ),
        ),
        new OA\Response(response: 401, description: 'Unauthenticated'),
    ],
)]
class UserOperation
{
}
