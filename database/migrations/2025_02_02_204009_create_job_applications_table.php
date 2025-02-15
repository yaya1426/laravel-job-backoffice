<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primary key as UUID
            $table->enum('status', ['applied', 'reviewed', 'rejected', 'hired']); // Application status
            $table->float('aiGeneratedScore', 3, 1)->default(0); // AI-generated score (0-10)
            $table->text('aiGeneratedFeedback')->nullable(); // AI-generated feedback explaining the score

            // Timestamps
            $table->timestamps();

            // Foreign keys
            $table->uuid('jobId'); // Job vacancy being applied for
            $table->uuid('resumeId'); // Resume used for this application
            $table->uuid('userId'); // User who applied

            // Foreign key constraints
            $table->foreign('jobId')->references('id')->on('job_vacancies')->onDelete('restrict');
            $table->foreign('resumeId')->references('id')->on('resumes')->onDelete('restrict');
            $table->foreign('userId')->references('id')->on('users')->onDelete('restrict');

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
