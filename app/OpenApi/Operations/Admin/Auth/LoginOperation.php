<?php

namespace App\OpenApi\Operations\Admin\Auth;

use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/admin/login',
    summary: 'Admin login and receive a bearer token',
    tags: ['Admin Auth'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['email', 'password'],
            properties: [
                new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@kjobs.com'),
                new OA\Property(property: 'password', type: 'string', format: 'password', example: 'Password'),
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
            response: 404,
            description: 'User not found',
            content: new OA\JsonContent(ref: '#/components/schemas/ApiErrorResponse'),
        ),
        new OA\Response(
            response: 401,
            description: 'Not an admin account',
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
