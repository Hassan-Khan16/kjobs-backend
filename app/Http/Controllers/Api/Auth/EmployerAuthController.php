<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EmployerRegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\EmployerProfile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class EmployerAuthController extends Controller
{
    public function register(EmployerRegisterRequest $request): JsonResponse
    {
        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->validated('contact_person_name'),
                'email' => $request->validated('email'),
                'password' => $request->validated('password'),
                'role' => 'employer',
                'is_active' => true,
            ]);

            EmployerProfile::create([
                'user_id' => $user->id,
                'company_name' => $request->validated('company_name'),
                'contact_person_name' => $request->validated('contact_person_name'),
                'phone' => $request->validated('phone'),
            ]);

            return $user->load('employerProfile');
        });

        return $this->tokenResponse($user, 'Registered successfully', 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->validated('email'))->first();

        if (! $user || ! $user->isEmployer()) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        if (! Hash::check($request->validated('password'), $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        if (! $user->is_active) {
            return ApiResponse::forbidden('Your account has been deactivated.');
        }

        $user->load('employerProfile');

        return $this->tokenResponse($user, 'Login successful');
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
