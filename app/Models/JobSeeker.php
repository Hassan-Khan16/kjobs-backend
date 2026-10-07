<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobSeeker extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'profile_photo',
        'headline',
        'bio',
        'location',
        'date_of_birth',
        'gender',
        'resume_path',
        'linkedin_url',
        'github_url',
        'website_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}