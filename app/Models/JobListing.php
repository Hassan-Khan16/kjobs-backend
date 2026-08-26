<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class JobListing extends Model
{
    protected $fillable = [
        'employer_profile_id',
        'title',
        'slug',
        'description',
        'location',
        'salary_min',
        'salary_max',
        'job_type',
        'experience_level',
        'status',
        'deadline',
    ];

    protected static function booted(): void
{
    static::creating(function (JobListing $job) {
        $job->slug = $job->generateUniqueSlug();
    });

    static::updating(function (JobListing $job) {
        if ($job->isDirty('title') || empty($job->slug)) {
            $job->slug = $job->generateUniqueSlug();
        }
    });
}

protected function generateUniqueSlug(): string
{
    $slug = Str::slug($this->title);
    $originalSlug = $slug;
    $counter = 1;

    while (
        static::where('slug', $slug)
            ->where('id', '!=', $this->id)
            ->exists()
    ) {
        $slug = $originalSlug . '-' . $counter++;
    }

    return $slug;
}

    public function employerProfile()
    {
        return $this->belongsTo(EmployerProfile::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}