<?php

namespace Tests\Feature;

use App\Models\JobSeeker;
use App\Models\EmployerProfile;
use App\Models\JobListing;
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

        $this->assertSame('job-seeker', $user->role);
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

    public function test_admin_can_list_and_view_job_seekers_and_job_listings(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $seeker = User::factory()->create(['role' => 'job-seeker']);
        $seeker->jobSeeker()->create([
            'headline' => 'Backend Developer',
            'location' => 'Toronto',
            'date_of_birth' => '1995-04-12',
            'gender' => 'female',
            'linkedin_url' => 'https://linkedin.com/in/seeker',
            'github_url' => 'https://github.com/seeker',
        ]);
        $legacySeeker = User::factory()->create(['role' => 'job-seeker']);

        $employer = User::factory()->create(['role' => 'employer']);
        $employerProfile = EmployerProfile::create([
            'user_id' => $employer->id,
            'company_name' => 'Example Company',
            'contact_person_name' => $employer->name,
        ]);
        $job = JobListing::create([
            'employer_profile_id' => $employerProfile->id,
            'title' => 'Laravel Developer',
            'description' => 'Build API features.',
            'location' => 'Remote',
            'job_type' => 'full-time',
            'experience_level' => 'mid',
            'status' => 'open',
        ]);

        $this->actingAs($admin, 'sanctum');

        $this->getJson('/api/admin/job-seekers')
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.items.0.id', $seeker->id)
            ->assertJsonPath('data.items.0.job_seeker.headline', 'Backend Developer')
            ->assertJsonFragment(['id' => $legacySeeker->id, 'job_seeker' => null]);

        $this->getJson("/api/admin/job-seekers/{$seeker->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $seeker->id)
            ->assertJsonPath('data.job_seeker.location', 'Toronto');

        $this->getJson("/api/admin/job-seekers/{$legacySeeker->id}")
            ->assertOk()
            ->assertJsonPath('data.job_seeker', null);

        $this->getJson('/api/admin/job-listings')
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.items.0.id', $job->id)
            ->assertJsonPath('data.items.0.employer_profile.company_name', 'Example Company');

        $this->getJson("/api/admin/job-listings/{$job->id}")
            ->assertOk()
            ->assertJsonPath('data.description', 'Build API features.')
            ->assertJsonPath('data.employer_profile_id', $employerProfile->id);
    }

    public function test_seeker_registration_uses_job_seeker_role(): void
    {
        $response = $this->postJson('/api/auth/user/register', [
            'name' => 'Registered Seeker',
            'email' => 'registered.seeker@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.user.role', 'job-seeker');

        $this->assertDatabaseHas('users', [
            'email' => 'registered.seeker@example.com',
            'role' => 'job-seeker',
        ]);
    }
}