<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->validated('email'))->first();

        if (! $user) {
            return ApiResponse::notFound('Invalid credentials.');
        }

        if (! $user->isAdmin()) {
            return ApiResponse::unauthorized('Login using user login page.');
        }

        if (! Hash::check($request->validated('password'), $user->password)) {
            return ApiResponse::unauthorized('These credentials do not match our records.');
        }

        if (! $user->is_active) {
            return ApiResponse::forbidden('Your account has been deactivated.');
        }

        $token = $user->createToken('auth')->plainTextToken;

        return ApiResponse::success([
            'user' => (new UserResource($user))->resolve(),
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Login successful');
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user || ! $user->isAdmin()) {
            return ApiResponse::unauthorized('Admin access required.');
        }

        $user->currentAccessToken()?->delete();

        return ApiResponse::success(null, 'Logged out successfully.');
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user || ! $user->isAdmin()) {
            return ApiResponse::unauthorized('Admin access required.');
        }

        return ApiResponse::success(
            (new UserResource($user))->resolve(),
            'Admin retrieved successfully',
        );
    }
}
