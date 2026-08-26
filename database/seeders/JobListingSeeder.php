<?php

namespace Database\Seeders;

use App\Models\JobListing;
use Illuminate\Database\Seeder;

class JobListingSeeder extends Seeder
{
    public function run(): void
    {
        $listings = [
            [
                'title' => 'Senior Laravel Developer',
                'description' => 'We are looking for an experienced Laravel developer to build and maintain scalable web applications.',
                'location' => 'Karachi, Pakistan',
                'salary_min' => 150000,
                'salary_max' => 250000,
                'job_type' => 'part-time',
                'experience_level' => 'senior',
                'status' => 'open',
                'deadline' => now()->addDays(30),
            ],
            [
                'title' => 'Frontend React Developer',
                'description' => 'Join our frontend team to build modern and responsive web applications using React and Next.js.',
                'location' => 'Lahore, Pakistan',
                'salary_min' => 100000,
                'salary_max' => 180000,
                'job_type' => 'remote',
                'experience_level' => 'mid',
                'status' => 'open',
                'deadline' => now()->addDays(25),
            ],
            [
                'title' => 'Junior Software Engineer',
                'description' => 'We are looking for a motivated junior software engineer to join our development team and work on real-world applications.',
                'location' => 'Islamabad, Pakistan',
                'salary_min' => 70000,
                'salary_max' => 100000,
                'job_type' => 'full-time',
                'experience_level' => 'junior',
                'status' => 'open',
                'deadline' => now()->addDays(20),
            ],
        ];

        foreach ($listings as $listing) {
            JobListing::updateOrCreate(
                [
                    'employer_profile_id' => 1,
                    'title' => $listing['title'],
                ],
                $listing,
            );
        }
    }
}
