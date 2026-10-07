<?php

namespace Tests\Feature;

use App\Models\JobSeeker;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\JobSeekerSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobSeekerTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_seeker_belongs_to_user_and_is_deleted_with_user(): void
    {
        $user = User::factory()->create();
        $jobSeeker = $user->jobSeeker()->create([
            'headline' => 'Product designer',
            'location' => 'Toronto',
            'date_of_birth' => '1995-04-12',
            'gender' => 'female',
            'linkedin_url' => 'https://linkedin.com/in/example',
            'github_url' => 'https://github.com/example',
        ]);

        $this->assertInstanceOf(JobSeeker::class, $user->jobSeeker);
        $this->assertSame($user->id, $jobSeeker->user->id);

        $user->delete();

        $this->assertDatabaseMissing('job_seekers', ['id' => $jobSeeker->id]);
    }

    public function test_job_seeker_seeder_creates_user_profiles_idempotently(): void
    {
        $this->seed(JobSeekerSeeder::class);
        $this->seed(JobSeekerSeeder::class);

        $user = User::where('email', 'alex.wilson@kjobs.com')->firstOrFail();

        $this->assertSame('user', $user->role);
        $this->assertInstanceOf(JobSeeker::class, $user->jobSeeker);
        $this->assertDatabaseCount('job_seekers', 2);
    }

    public function test_database_seeder_can_run_repeatedly_without_duplicates(): void
    {
        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('users', 6);
        $this->assertDatabaseCount('employer_profiles', 3);
        $this->assertDatabaseCount('job_seekers', 2);
        $this->assertDatabaseCount('job_listings', 3);
        $this->assertDatabaseCount('applications', 2);

        $jobSeeker = User::where('email', 'alex.wilson@kjobs.com')->firstOrFail();
        $this->assertSame(2, $jobSeeker->applications()->count());
    }
}