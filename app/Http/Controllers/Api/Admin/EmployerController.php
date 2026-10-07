<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Http\Requests\Admin\ListPaginatedEmployerRequest;
use App\Http\Requests\Admin\StoreEmployerRequest;
use App\Http\Requests\Admin\UpdateEmployerRequest;
use App\Http\Requests\Admin\UpdateEmployerPasswordRequest;
use App\Http\Resources\EmployerResource;
use App\Http\Resources\PaginatedResource;
use App\Models\EmployerProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployerController extends Controller
{
    public function index(ListPaginatedEmployerRequest $request)
    {
        $query = EmployerProfile::query()
            ->with('user')
            ->whereHas('user', function ($q) {
                $q->where('role', 'employer');
            })
            ->when(
                $request->search,
                fn ($q) =>
                $q->where(function ($query) use ($request) {
                    $query
                        ->where('company_name', 'like', "%{$request->search}%")
                        ->orWhere('contact_person_name', 'like', "%{$request->search}%")
                        ->orWhereHas('user', function ($userQuery) use ($request) {
                            $userQuery->where('email', 'like', "%{$request->search}%");
                        });
                })
            )
            ->when(
                $request->status,
                fn ($q) =>
                $q->whereHas('user', function ($userQuery) use ($request) {
                    $userQuery->where(
                        'is_active',
                        $request->status === 'active'
                    );
                })
            );

        $employers = $query->paginate($request->limit);

        return ApiResponse::success(
            new PaginatedResource($employers, EmployerResource::class),
            'Employers retrieved successfully'
        );
    }

    public function show($id)
    {
        $employer = EmployerProfile::with('user')->findOrFail($id);

        return ApiResponse::success(
            new EmployerResource($employer),
            'Employer retrieved successfully'
        );
    }

    public function store(StoreEmployerRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->contact_person_name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => 'employer',
                'is_active' => true,
            ]);

            $employer = EmployerProfile::create([
                'user_id' => $user->id,
                'company_name' => $request->company_name,
                'contact_person_name' => $request->contact_person_name,
                'phone' => $request->phone,
                'company_description' => $request->company_description,
                'website' => $request->website,
                'logo' => $request->logo,
            ]);

            return ApiResponse::success(
                new EmployerResource($employer->load('user')),
                'Employer created successfully'
            );
        });
    }

    public function update(UpdateEmployerRequest $request, $id)
    {
        $employer = EmployerProfile::with('user')->findOrFail($id);

        return DB::transaction(function () use ($request, $employer) {
            // Update user fields
            if ($request->has('email')) {
                $employer->user->update(['email' => $request->email]);
            }
            if ($request->has('contact_person_name')) {
                $employer->user->update(['name' => $request->contact_person_name]);
            }

            // Update employer profile fields
            $employer->update([
                'company_name' => $request->company_name ?? $employer->company_name,
                'contact_person_name' => $request->contact_person_name ?? $employer->contact_person_name,
                'phone' => $request->phone ?? $employer->phone,
                'company_description' => $request->company_description ?? $employer->company_description,
                'website' => $request->website ?? $employer->website,
                'logo' => $request->logo ?? $employer->logo,
            ]);

            return ApiResponse::success(
                new EmployerResource($employer->load('user')),
                'Employer updated successfully'
            );
        });
    }

    public function updatePassword(UpdateEmployerPasswordRequest $request, $id)
    {
        $employer = EmployerProfile::with('user')->findOrFail($id);
        $employer->user->update([
            'password' => $request->validated('password'),
        ]);

        return ApiResponse::success(
            null,
            'Employer password updated successfully'
        );
    }

    public function destroy($id)
    {
        $employer = EmployerProfile::findOrFail($id);

        return DB::transaction(function () use ($employer) {
            $userId = $employer->user_id;
            $employer->delete();
            User::where('id', $userId)->delete();

            return ApiResponse::success(
                null,
                'Employer deleted successfully'
            );
        });
    }

    public function toggleStatus(Request $request, $id)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $employer = EmployerProfile::with('user')->findOrFail($id);
        $employer->user->update(['is_active' => $request->is_active]);

        return ApiResponse::success(
            new EmployerResource($employer->load('user')),
            'Employer status updated successfully'
        );
    }
}
