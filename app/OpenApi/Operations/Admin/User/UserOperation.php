<?php

namespace App\OpenApi\Operations\Admin\User;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/admin/users',
    summary: 'Get users',
    tags: ['Admin Users'],
    security: [['sanctum' => []]],

    parameters: [

        new OA\Parameter(
            name: 'page',
            in: 'query',
            required: false,
            description: 'Page number',
            schema: new OA\Schema(
                type: 'integer',
                default: 1,
                minimum: 1
            )
        ),

        new OA\Parameter(
            name: 'limit',
            in: 'query',
            required: false,
            description: 'Items per page',
            schema: new OA\Schema(
                type: 'integer',
                default: 10,
                minimum: 1,
                maximum: 100
            )
        ),

        new OA\Parameter(
            name: 'search',
            in: 'query',
            required: false,
            description: 'Search by user name or email',
            schema: new OA\Schema(
                type: 'string',
                maxLength: 200,
                example: 'hassan'
            )
        ),

        new OA\Parameter(
            name: 'status',
            in: 'query',
            required: false,
            description: 'Filter users by status',
            schema: new OA\Schema(
                type: 'string',
                enum: [
                    'active',
                    'inactive'
                ],
                example: 'active'
            )
        ),
    ],

    responses: [

        new OA\Response(
            response: 200,
            description: 'Users retrieved successfully',

            content: new OA\JsonContent(

                properties: [

                    new OA\Property(
                        property: 'status',
                        type: 'boolean',
                        example: true
                    ),

                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: 'Users retrieved successfully'
                    ),

                    new OA\Property(

                        property: 'data',

                        properties: [

                            new OA\Property(
                                property: 'items',
                                type: 'array',
                                items: new OA\Items(
                                    ref: '#/components/schemas/User'
                                )
                            ),

                            new OA\Property(

                                property: 'pagination',

                                properties: [

                                    new OA\Property(
                                        property: 'page',
                                        type: 'integer',
                                        example: 1
                                    ),

                                    new OA\Property(
                                        property: 'limit',
                                        type: 'integer',
                                        example: 10
                                    ),

                                    new OA\Property(
                                        property: 'total',
                                        type: 'integer',
                                        example: 120
                                    ),

                                    new OA\Property(
                                        property: 'last_page',
                                        type: 'integer',
                                        example: 12
                                    ),

                                    new OA\Property(
                                        property: 'has_more',
                                        type: 'boolean',
                                        example: true
                                    ),

                                ]

                            ),

                        ]

                    ),

                ]

            )
        ),

        new OA\Response(
            response: 401,
            description: 'Unauthorized'
        ),
    ]
)]
class UserOperation
{
}