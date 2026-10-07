<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ListPaginatedJobListingRequest;
use App\Http\Resources\JobListingResource;
use App\Http\Resources\PaginatedResource;
use App\Models\JobListing;

class JobListingController extends Controller
{
    public function index(ListPaginatedJobListingRequest $request)
    {
        $jobListings = JobListing::query()
            ->with('employerProfile.user')
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query
                        ->where('title', 'like', "%{$request->search}%")
                        ->orWhere('location', 'like', "%{$request->search}%")
                        ->orWhereHas('employerProfile', fn ($employerQuery) => $employerQuery
                            ->where('company_name', 'like', "%{$request->search}%"));
                });
            })
            ->when($request->status, fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->paginate($request->limit);

        return ApiResponse::success(
            new PaginatedResource($jobListings, JobListingResource::class),
            'Job listings retrieved successfully',
        );
    }

    public function show(string $id)
    {
        $jobListing = JobListing::query()
            ->with('employerProfile.user')
            ->findOrFail($id);

        return ApiResponse::success(
            new JobListingResource($jobListing),
            'Job listing retrieved successfully',
        );
    }
}