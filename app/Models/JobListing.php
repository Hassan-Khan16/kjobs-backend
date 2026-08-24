<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function employerProfile()
    {
        return $this->belongsTo(EmployerProfile::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}