<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    private static function response(
        bool $status,
        string $message,
        mixed $data = null,
        mixed $errors = null,
        int $code = 200
    ): JsonResponse {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $status ? $data : null,
            'errors' => $status ? null : $errors,
        ], $code);
    }

    public static function success(
        mixed $data = null,
        string $message = 'Success',
        int $code = 200
    ): JsonResponse {
        return self::response(
            true,
            $message,
            $data,
            null,
            $code
        );
    }

    public static function error(
        string $message = 'Error',
        mixed $errors = null,
        int $code = 400
    ): JsonResponse {
        return self::response(
            false,
            $message,
            null,
            $errors,
            $code
        );
    }

    public static function unauthorized(
        string $message = 'Unauthorized'
    ): JsonResponse {
        return self::error(
            $message,
            null,
            401
        );
    }

    public static function forbidden(
        string $message = 'Forbidden'
    ): JsonResponse {
        return self::error(
            $message,
            null,
            403
        );
    }

    public static function notFound(
        string $message = 'Not Found'
    ): JsonResponse {
        return self::error(
            $message,
            null,
            404
        );
    }

    public static function validation(
        mixed $errors,
        string $message = 'Validation Error'
    ): JsonResponse {
        return self::error(
            $message,
            $errors,
            422
        );
    }
}