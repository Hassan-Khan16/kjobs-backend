<?php

use App\Http\Controllers\Api\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Api\Admin\EmployerController as AdminEmployerController;
use App\Http\Controllers\Api\Admin\JobListingController as AdminJobListingController;
use App\Http\Controllers\Api\Admin\JobSeekerController as AdminJobSeekerController;
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


Route::post('/admin/login', [AdminAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('auth')->group(function () {
        Route::get('/me', [AuthSessionController::class, 'me']);
        Route::post('/logout', [AuthSessionController::class, 'logout']);
    });

    Route::prefix('admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout']);
        Route::get('/me', [AdminAuthController::class, 'me']);
        Route::post('/job-seekers', [AdminJobSeekerController::class, 'store']);
        Route::get('/job-seekers', [AdminJobSeekerController::class, 'index']);
        Route::get('/job-seekers/{id}', [AdminJobSeekerController::class, 'show']);
        Route::put('/job-seekers/{id}', [AdminJobSeekerController::class, 'update']);
        Route::patch('/job-seekers/{id}/password', [AdminJobSeekerController::class, 'updatePassword']);
        Route::patch('/job-seekers/{id}/status', [AdminJobSeekerController::class, 'toggleStatus']);

        Route::get('/job-listings', [AdminJobListingController::class, 'index']);
        Route::get('/job-listings/{id}', [AdminJobListingController::class, 'show']);

        Route::prefix('employers')->group(function () {
            Route::get('/', [AdminEmployerController::class, 'index']);
            Route::post('/', [AdminEmployerController::class, 'store']);
            Route::get('/{id}', [AdminEmployerController::class, 'show']);
            Route::put('/{id}', [AdminEmployerController::class, 'update']);
            Route::patch('/{id}/password', [AdminEmployerController::class, 'updatePassword']);
            Route::delete('/{id}', [AdminEmployerController::class, 'destroy']);
            Route::patch('/{id}/status', [AdminEmployerController::class, 'toggleStatus']);
        });
    });
});
