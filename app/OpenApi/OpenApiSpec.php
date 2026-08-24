<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(title: 'KJobs API', version: '1.0.0')]
#[OA\Server(url: '/api', description: 'API base path')]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'Token',
)]
class OpenApiSpec
{
}
