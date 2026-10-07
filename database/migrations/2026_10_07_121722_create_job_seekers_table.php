<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_seekers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();
            $table->string('phone')->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('headline');
            $table->text('bio')->nullable();
            $table->string('location');
            $table->date('date_of_birth');
            $table->string('gender');
            $table->string('resume_path')->nullable();
            $table->string('linkedin_url');
            $table->string('github_url');
            $table->string('website_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_seekers');
    }
};