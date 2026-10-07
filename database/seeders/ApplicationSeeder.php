<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobSeeker = User::where('email', 'alex.wilson@kjobs.com')
            ->where('role', 'job-seeker')
            ->whereHas('jobSeeker')
            ->firstOrFail();
        $employerProfile = User::where('email', 'john@techcorp.com')
            ->where('role', 'employer')
            ->firstOrFail()
            ->employerProfile;

        $applications = [
            [
                'job_title' => 'Senior Laravel Developer',
                'resume_path' => 'resumes/john-doe-resume.pdf',
                'cover_letter' => 'I am excited to apply for the Senior Laravel Developer position. I have extensive experience building scalable Laravel applications.',
                'status' => 'applied',
                'applied_at' => now()->subDays(3),
            ],
            [
                'job_title' => 'Frontend React Developer',
                'resume_path' => 'resumes/alex-wilson-resume.pdf',
                'cover_letter' => 'I am interested in the Frontend React Developer position and believe my experience with React and Next.js makes me a strong candidate.',
                'status' => 'rejected',
                'applied_at' => now()->subDay(),
            ],
        ];

        foreach ($applications as $application) {
            $jobListing = $employerProfile->jobs()
                ->where('title', $application['job_title'])
                ->firstOrFail();
            unset($application['job_title']);
            $application['job_listing_id'] = $jobListing->id;
            $application['user_id'] = $jobSeeker->id;

            Application::updateOrCreate(
                [
                    'job_listing_id' => $application['job_listing_id'],
                    'user_id' => $application['user_id'],
                ],
                $application,
            );
        }
    }
}
