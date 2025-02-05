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
        Schema::create('job_vacancies', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primary key as UUID
            $table->string('title'); // Job title
            $table->text('description'); // Job description
            $table->string('location'); // Job location
            $table->enum('type', ['full-time', 'part-time', 'remote'])->default('full-time'); // Job type
            $table->decimal('salary', 10, 2); // Salary with precision and scale

            // Timestamps
            $table->timestamps();

            // Foreign keys
            $table->uuid('companyId');
            $table->uuid('categoryId');

            // Foreign key constraints
            $table->foreign('companyId')->references('id')->on('companies')->onDelete('restrict');
            $table->foreign('categoryId')->references('id')->on('job_categories')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_vacancies');
    }
};
