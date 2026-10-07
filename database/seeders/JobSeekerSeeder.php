<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class JobSeekerSeeder extends Seeder
{
    public function run(): void
    {
        $jobSeekers = [
            [
                'user' => [
                    'name' => 'Alex Wilson',
                    'email' => 'alex.wilson@kjobs.com',
                ],
                'profile' => [
                    'phone' => '+1-555-0201',
                    'headline' => 'Frontend React Developer',
                    'bio' => 'Frontend developer with experience building accessible web applications.',
                    'location' => 'Toronto, Canada',
                    'date_of_birth' => '1995-04-12',
                    'gender' => 'male',
                    'resume_path' => 'resumes/alex-wilson-resume.pdf',
                    'linkedin_url' => 'https://www.linkedin.com/in/alex-wilson',
                    'github_url' => 'https://github.com/alex-wilson',
                    'website_url' => 'https://alexwilson.dev',
                ],
            ],
            [
                'user' => [
                    'name' => 'Maya Ahmed',
                    'email' => 'maya.ahmed@kjobs.com',
                ],
                'profile' => [
                    'phone' => '+1-555-0202',
                    'headline' => 'Product Designer',
                    'bio' => 'Product designer focused on clear, user-centered digital experiences.',
                    'location' => 'Lahore, Pakistan',
                    'date_of_birth' => '1997-08-23',
                    'gender' => 'female',
                    'resume_path' => 'resumes/maya-ahmed-resume.pdf',
                    'linkedin_url' => 'https://www.linkedin.com/in/maya-ahmed',
                    'github_url' => 'https://github.com/maya-ahmed',
                    'website_url' => null,
                ],
            ],
        ];

        foreach ($jobSeekers as $jobSeekerData) {
            $user = User::updateOrCreate(
                ['email' => $jobSeekerData['user']['email']],
                [
                    'name' => $jobSeekerData['user']['name'],
                    'password' => 'Password',
                    'role' => 'user',
                    'is_active' => true,
                ],
            );

            $user->jobSeeker()->updateOrCreate([], $jobSeekerData['profile']);
        }
    }
}