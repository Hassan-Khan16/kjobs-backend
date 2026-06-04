<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\EmployerProfileResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthSessionController extends Controller
{
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->isEmployer()) {
            $user->load('employerProfile');
        }

        $payload = (new UserResource($user))->resolve();

        if ($user->employerProfile) {
            $payload['employer_profile'] = (new EmployerProfileResource($user->employerProfile))->resolve();
        }

        return ApiResponse::success($payload, 'User retrieved successfully');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return ApiResponse::success(null, 'Logged out successfully.');
    }
}
