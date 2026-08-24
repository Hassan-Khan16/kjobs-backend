<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
            'role' => $request->validated('role'),
            'is_active' => true,
        ]);

        return $this->tokenResponse($user, 'Registered successfully', 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->validated('email'))->first();

        if ($user && $user->isAdmin()) {
            return ApiResponse::unauthorized('Login using admin login page.');
        }

        if (! $user || ! Hash::check($request->validated('password'), $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        if (! $user->is_active) {
            return ApiResponse::forbidden('Your account has been deactivated.');
        }

        return $this->tokenResponse($user, 'Login successful');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return ApiResponse::success(null, 'Logged out successfully.');
    }

    public function user(Request $request): JsonResponse
    {
        return ApiResponse::success(
            (new UserResource($request->user()))->resolve(),
            'User retrieved successfully',
        );
    }

    private function tokenResponse(User $user, string $message, int $status = 200): JsonResponse
    {
        $token = $user->createToken('auth')->plainTextToken;

        return ApiResponse::success([
            'user' => (new UserResource($user))->resolve(),
            'token' => $token,
            'token_type' => 'Bearer',
        ], $message, $status);
    }
}
