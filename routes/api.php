<?php

use App\Http\Controllers\Api\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\Auth\AuthSessionController;
use App\Http\Controllers\Api\Auth\EmployerAuthController;
use App\Http\Controllers\Api\Auth\UserAuthController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::prefix('auth/user')->group(function () {
    Route::post('/register', [UserAuthController::class, 'register']);
    Route::post('/login', [UserAuthController::class, 'login']);
});

Route::prefix('auth/employer')->group(function () {
    Route::post('/register', [EmployerAuthController::class, 'register']);
    Route::post('/login', [EmployerAuthController::class, 'login']);
});


Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::get('/users', [AdminUserController::class, 'index']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('auth')->group(function () {
        Route::get('/me', [AuthSessionController::class, 'me']);
        Route::post('/logout', [AuthSessionController::class, 'logout']);
    });

    Route::post('/admin/logout', [AdminAuthController::class, 'logout']);
    Route::get('/admin/me', [AdminAuthController::class, 'me']);
});
