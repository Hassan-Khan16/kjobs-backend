<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobListingResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employer_profile_id' => $this->employer_profile_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'location' => $this->location,
            'salary_min' => $this->salary_min,
            'salary_max' => $this->salary_max,
            'job_type' => $this->job_type,
            'experience_level' => $this->experience_level,
            'status' => $this->status,
            'deadline' => $this->deadline,
            'employer_profile' => [
                'id' => $this->employerProfile->id,
                'company_name' => $this->employerProfile->company_name,
                'user' => [
                    'name' => $this->employerProfile->user->name,
                ],
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}