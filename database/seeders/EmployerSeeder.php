<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class EmployerSeeder extends Seeder
{
    public function run(): void
    {
        $employers = [
            [
                'name' => 'John Smith',
                'email' => 'john@techcorp.com',
                'password' => bcrypt('password'),
                'company_name' => 'TechCorp Inc.',
                'contact_person_name' => 'John Smith',
                'phone' => '+1-555-0101',
                'company_description' => 'A leading technology company specializing in software development and cloud solutions.',
                'website' => 'https://techcorp.com',
                'logo' => null,
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah@innovate.io',
                'password' => bcrypt('password'),
                'company_name' => 'Innovate Labs',
                'contact_person_name' => 'Sarah Johnson',
                'phone' => '+1-555-0102',
                'company_description' => 'Innovative startup focused on AI and machine learning solutions.',
                'website' => 'https://innovate.io',
                'logo' => null,
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael@designstudio.co',
                'password' => bcrypt('password'),
                'company_name' => 'Design Studio Co.',
                'contact_person_name' => 'Michael Chen',
                'phone' => '+1-555-0103',
                'company_description' => 'Creative design agency specializing in UI/UX and brand identity.',
                'website' => 'https://designstudio.co',
                'logo' => null,
            ],
        ];

        foreach ($employers as $employerData) {
            $user = User::updateOrCreate(
                ['email' => $employerData['email']],
                [
                    'name' => $employerData['name'],
                    'password' => $employerData['password'],
                    'role' => 'employer',
                    'is_active' => true,
                ],
            );

            $user->employerProfile()->updateOrCreate(
                [],
                [
                    'company_name' => $employerData['company_name'],
                    'contact_person_name' => $employerData['contact_person_name'],
                    'phone' => $employerData['phone'],
                    'company_description' => $employerData['company_description'],
                    'website' => $employerData['website'],
                    'logo' => $employerData['logo'],
                ],
            );
        }
    }
}
