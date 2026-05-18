<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@kjobs.com'],
            [
                'name' =>  'Admin',
                'password' =>  'Password',
                'role' => 'admin',
                'is_active' => true,
            ],
        );
    }
}
