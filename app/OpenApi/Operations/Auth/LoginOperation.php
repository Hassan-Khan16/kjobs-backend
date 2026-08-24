<?php

namespace App\OpenApi\Operations\Auth;

use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/login',
    summary: 'Login and receive a bearer token',
    tags: ['Auth'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['email', 'password'],
            properties: [
                new OA\Property(property: 'email', type: 'string', format: 'email', example: 'jane@example.com'),
                new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
            ],
        ),
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: 'Login successful',
            content: new OA\JsonContent(ref: '#/components/schemas/AuthTokenResponse'),
        ),
        new OA\Response(
            response: 401,
            description: 'Admin must use admin login',
            content: new OA\JsonContent(ref: '#/components/schemas/ApiErrorResponse'),
        ),
        new OA\Response(
            response: 403,
            description: 'Account deactivated',
            content: new OA\JsonContent(ref: '#/components/schemas/ApiErrorResponse'),
        ),
        new OA\Response(response: 422, description: 'Invalid credentials'),
    ],
)]
class LoginOperation
{
}
