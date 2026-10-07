<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ListPaginatedJobSeekerRequest;
use App\Http\Requests\Admin\StoreJobSeekerRequest;
use App\Http\Requests\Admin\UpdateJobSeekerPasswordRequest;
use App\Http\Requests\Admin\UpdateJobSeekerRequest;
use App\Http\Resources\JobSeekerResource;
use App\Http\Resources\PaginatedResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class JobSeekerController extends Controller
{
    public function store(StoreJobSeekerRequest $request)
    {
        $data = $request->validated();
        $password = config('services.accounts.default_password');
        $user = DB::transaction(function () use ($data, $password) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $password,
                'role' => 'job-seeker',
                'is_active' => true,
            ]);

            $user->jobSeeker()->create(collect($data)->except(['name', 'email'])->all());

            return $user->load('jobSeeker');
        });

        Mail::raw(
            "Your KJobs account is ready. Your temporary password is: {$password}",
            fn ($message) => $message->to($user->email)->subject('Your KJobs account'),
        );

        return ApiResponse::success(
            new JobSeekerResource($user),
            'Job seeker created successfully',
            201,
        );
    }

    public function index(ListPaginatedJobSeekerRequest $request)
    {
        $jobSeekers = User::query()
            ->with('jobSeeker')
            ->where('role', 'job-seeker')
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query
                        ->where('name', 'like', "%{$request->search}%")
                        ->orWhere('email', 'like', "%{$request->search}%")
                        ->orWhereHas('jobSeeker', function ($profileQuery) use ($request) {
                            $profileQuery
                                ->where('headline', 'like', "%{$request->search}%")
                                ->orWhere('location', 'like', "%{$request->search}%");
                        });
                });
            })
            ->when($request->status, fn ($query) => $query->where(
                'is_active',
                $request->status === 'active',
            ))
            ->latest()
            ->paginate($request->limit);

        return ApiResponse::success(
            new PaginatedResource($jobSeekers, JobSeekerResource::class),
            'Job seekers retrieved successfully',
        );
    }

    public function show(string $id)
    {
        $jobSeeker = User::query()
            ->with('jobSeeker')
            ->where('role', 'job-seeker')
            ->findOrFail($id);

        return ApiResponse::success(
            new JobSeekerResource($jobSeeker),
            'Job seeker retrieved successfully',
        );
    }

    public function update(UpdateJobSeekerRequest $request, string $id)
    {
        $user = User::query()
            ->where('role', 'job-seeker')
            ->findOrFail($id);
        $data = $request->validated();

        DB::transaction(function () use ($user, $data) {
            $user->update(collect($data)->only(['name', 'email'])->all());
            $user->jobSeeker()->updateOrCreate(
                [],
                collect($data)->except(['name', 'email'])->all(),
            );
        });

        return ApiResponse::success(
            new JobSeekerResource($user->load('jobSeeker')),
            'Job seeker updated successfully',
        );
    }

    public function updatePassword(UpdateJobSeekerPasswordRequest $request, string $id)
    {
        $user = User::query()->where('role', 'job-seeker')->findOrFail($id);
        $user->update(['password' => $request->validated('password')]);

        return ApiResponse::success(null, 'Job seeker password updated successfully');
    }

    public function toggleStatus(Request $request, string $id)
    {
        $validated = $request->validate(['is_active' => ['required', 'boolean']]);
        $user = User::query()->where('role', 'job-seeker')->findOrFail($id);
        $user->update(['is_active' => $validated['is_active']]);

        return ApiResponse::success(
            new JobSeekerResource($user->load('jobSeeker')),
            'Job seeker status updated successfully',
        );
    }
}