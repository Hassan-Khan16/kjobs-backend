<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobSeekerResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $profile = $this->jobSeeker;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'job_seeker' => $profile ? [
                'id' => $profile->id,
                'phone' => $profile->phone,
                'profile_photo' => $profile->profile_photo,
                'headline' => $profile->headline,
                'bio' => $profile->bio,
                'location' => $profile->location,
                'date_of_birth' => $profile->date_of_birth,
                'gender' => $profile->gender,
                'resume_path' => $profile->resume_path,
                'linkedin_url' => $profile->linkedin_url,
                'github_url' => $profile->github_url,
                'website_url' => $profile->website_url,
                'created_at' => $profile->created_at,
                'updated_at' => $profile->updated_at,
            ] : null,
        ];
    }
}