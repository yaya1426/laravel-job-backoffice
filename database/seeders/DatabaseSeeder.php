<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\JobVacancy;
use App\Models\JobApplication;
use App\Models\JobCategory;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Check if the root admin user already exists
        User::firstOrCreate(
            ['email' => 'admin@shaghal.com'],
            [
                'id' => Str::uuid(),
                'name' => 'Root Admin',
                'password' => bcrypt('123123'),
                'role' => 'admin'
            ]
        );

        // Seed job categories manually to ensure uniqueness
        $categories = collect([
            'Web Development',
            'Backend',
            'Frontend',
            'Data Science',
            'DevOps',
        ])->map(function ($category) {
            return JobCategory::firstOrCreate([
                'name' => $category,
            ], [
                'id' => Str::uuid(),
            ]);
        });

        // Seed 10 different users with the 'company-owner' role
        $owners = User::factory(10)->create([
            'role' => 'company-owner',
        ]);

        // Assign companies to the owners and create job vacancies for each company
        $owners->each(function ($owner) use ($categories) {
            $companies = Company::factory(10)->create([
                'ownerId' => $owner->id,
            ]);

            $companies->each(function ($company) use ($categories) {
                JobVacancy::factory(5)->create([
                    'companyId' => $company->id,
                    'categoryId' => $categories->random()->id,  // Random category for each job
                ]);
            });
        });

        // Seed 20 job seekers and their resumes
        $jobSeekers = User::factory(20)->create([
            'role' => 'job-seeker',
        ]);

        $jobSeekers->each(function ($jobSeeker) {
            // Create a resume for each job seeker
            Resume::factory()->create([
                'userId' => $jobSeeker->id,
            ]);
        });

        // Create job applications for job seekers
        $jobSeekers->each(function ($jobSeeker) {
            // Each job seeker applies for 3 random job vacancies
            $randomVacancies = JobVacancy::inRandomOrder()->take(3)->get();

            $randomVacancies->each(function ($vacancy) use ($jobSeeker) {
                $resume = $jobSeeker->resumes()->first();  // Use the job seeker's resume

                JobApplication::factory()->create([
                    'jobId' => $vacancy->id,
                    'userId' => $jobSeeker->id,
                    'resumeId' => $resume->id,
                ]);
            });
        });
    }
}
