<?php

namespace App\OpenApi\Operations\Admin\User;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/admin/users',
    summary: 'Get all users',
    tags: ['Admin Users'],
    security: [['sanctum' => []]],
    responses: [
        new OA\Response(
            response: 200,
            description: 'All users',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'All users retrieved successfully'),
                    new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/User')),
                ],
            ),
        ),
        new OA\Response(response: 401, description: 'Unauthorized'),
    ],
)]
class UserOperation
{
}
