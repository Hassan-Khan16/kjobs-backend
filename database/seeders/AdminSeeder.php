<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@kjobs.com','role' => 'admin'],
            [
                'name' =>  'Admin',
                'password' =>  'Password',
                'is_active' => true,
            ],
        );
    }
}
