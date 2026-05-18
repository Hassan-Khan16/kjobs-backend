<?php

namespace App\OpenApi\Operations\Auth;

use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/register',
    summary: 'Register a new user',
    tags: ['Auth'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'email', 'password', 'password_confirmation', 'role'],
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Jane Doe'),
                new OA\Property(property: 'email', type: 'string', format: 'email', example: 'jane@example.com'),
                new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
                new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'password123'),
                new OA\Property(property: 'role', type: 'string', enum: ['user', 'employer'], example: 'user'),
            ],
        ),
    ),
    responses: [
        new OA\Response(
            response: 201,
            description: 'User registered',
            content: new OA\JsonContent(ref: '#/components/schemas/AuthTokenResponse'),
        ),
        new OA\Response(response: 422, description: 'Validation error'),
    ],
)]
class RegisterOperation
{
}
