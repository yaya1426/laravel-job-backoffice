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
        Schema::create('resumes', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primary key as UUID
            $table->string('filename'); // Resume file name
            $table->string('fileUri'); // Path/URI to resume file
            $table->string('contactDetails'); // Contact details (phone, email, etc.)
            $table->longText('summary'); // Summary or objective of the candidate
            $table->longText('skills'); // Comma-separated or structured skill details
            $table->longText('experience'); // Work experience details
            $table->longText('education'); // Education details

            // Timestamps
            $table->timestamps();

            // Foreign keys
            $table->uuid('userId'); // Foreign key to the user who owns this resume

            // Foreign key constraint with cascading delete
            $table->foreign('userId')->references('id')->on('users')->onDelete('restrict');

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resumes');
    }
};
