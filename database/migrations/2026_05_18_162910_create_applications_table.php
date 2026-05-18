<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {

            $table->id();

            $table->foreignId('job_listing_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->string('resume_path')
                  ->nullable();

            $table->longText('cover_letter')
                  ->nullable();

            $table->enum('status', [
                'applied',
                'reviewing',
                'shortlisted',
                'rejected',
                'hired'
            ])->default('applied');

            $table->timestamp('applied_at')
                  ->useCurrent();

            $table->unique([
                'job_listing_id',
                'user_id'
            ]);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};