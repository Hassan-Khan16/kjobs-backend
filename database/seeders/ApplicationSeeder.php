<?php

namespace Database\Seeders;

use App\Models\Application;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $applications = [
            [
                'job_listing_id' => 1,
                'user_id' => 2,
                'resume_path' => 'resumes/john-doe-resume.pdf',
                'cover_letter' => 'I am excited to apply for the Senior Laravel Developer position. I have extensive experience building scalable Laravel applications.',
                'status' => 'applied',
                'applied_at' => now()->subDays(3),
            ],
            [
                'job_listing_id' => 2,
                'user_id' => 2,
                'resume_path' => 'resumes/alex-wilson-resume.pdf',
                'cover_letter' => 'I am interested in the Frontend React Developer position and believe my experience with React and Next.js makes me a strong candidate.',
                'status' => 'rejected',
                'applied_at' => now()->subDay(),
            ],
        ];

        foreach ($applications as $application) {
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
