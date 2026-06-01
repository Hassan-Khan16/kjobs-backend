<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\Admin\ListPaginatedUserRequest;
use App\Http\Resources\PaginatedResource;

class UserController extends Controller
{
    public function index(ListPaginatedUserRequest $request)
    {
        $users = User::query()
            ->where('role', '!=', 'admin')
            ->when(
                $request->search,
                fn ($q) =>
                $q->where(function ($query) use ($request) {
    
                    $query
                        ->where(
                            'name',
                            'like',
                            "%{$request->search}%"
                        )
                        ->orWhere(
                            'email',
                            'like',
                            "%{$request->search}%"
                        );
    
                })
            )
    
            ->when(
                $request->status,
                fn ($q) =>
                $q->where(
                    'is_active',
                    $request->status === 'active'
                )
            )
            ->when(
                $request->role,
                fn ($q) =>
                $q->where(
                    'role',
                    $request->role
                )
            )
    
            ->paginate(
                $request->limit
            );
    
        return ApiResponse::success(
            new PaginatedResource(
                $users,
                UserResource::class
            ),
            'Users retrieved successfully'
        );
    }
}
