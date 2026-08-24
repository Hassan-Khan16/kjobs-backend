<?php

namespace App\OpenApi\Operations\Auth;

use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/logout',
    summary: 'Revoke the current access token',
    tags: ['Auth'],
    security: [['sanctum' => []]],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Logged out',
            content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccessResponse'),
        ),
        new OA\Response(response: 401, description: 'Unauthenticated'),
    ],
)]
class LogoutOperation
{
}
