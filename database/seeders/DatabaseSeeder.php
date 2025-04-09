<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\JobVacancy;
use App\Models\JobApplication;
use App\Models\JobCategory;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Load job data
        $jobData = json_decode(file_get_contents(database_path('data/job_data.json')), true);
        
        // Load job applications data
        $jobApplicationsData = json_decode(file_get_contents(database_path('data/job_applications.json')), true);

        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@shaghal.com'],
            [
                'id' => Str::uuid(),
                'name' => 'Root Admin',
                'password' => Hash::make('123123'),
                'role' => 'admin'
            ]
        );

        // Create job categories
        foreach ($jobData['jobCategories'] as $categoryName) {
            JobCategory::firstOrCreate(
                ['name' => $categoryName],
                ['id' => Str::uuid()]
            );
        }

        // Create companies
        foreach ($jobData['companies'] as $company) {
            // Create company owner
            $owner = User::firstOrCreate(
                ['email' => fake()->unique()->safeEmail()],
                [
                    'id' => Str::uuid(),
                    'role' => 'company-owner',
                    'name' => fake()->name(),
                    'password' => Hash::make('123123')
                ]
            );

            // Create company
            Company::firstOrCreate(
                ['name' => $company['name']],
                [
                    'id' => Str::uuid(), 
                    'ownerId' => $owner->id,
                    'address' => $company['address'],
                    'industry' => $company['industry'],
                    'website' => $company['website']
                ]
            );
        }

        // Create job vacancies
        foreach ($jobData['jobVacancies'] as $jobVacancy) {
            // Find the company
            $company = Company::where('name', $jobVacancy['company'])->first();
            
            // Find the category
            $category = JobCategory::where('name', $jobVacancy['category'])->first();
            
            if ($company && $category) {
                JobVacancy::firstOrCreate(
                    [
                        'title' => $jobVacancy['title'],
                        'companyId' => $company->id
                    ],
                    [
                        'id' => Str::uuid(),
                        'description' => $jobVacancy['description'],
                        'location' => $jobVacancy['location'],
                        'type' => $jobVacancy['type'],
                        'salary' => $jobVacancy['salary'],
                        'categoryId' => $category->id,
                        'view_count' => 0 // Don't forget to add this later, not in the Seeders video ya Yahya
                    ]
                );
            }
        }

        // Create job applications with resumes
        foreach ($jobApplicationsData['jobApplications'] as $jobApplication) {
            // Create a user for the applicant
            $applicant = User::firstOrCreate(
                ['email' => fake()->unique()->safeEmail()],
                [
                    'id' => Str::uuid(),
                    'role' => 'job-seeker',
                    'name' => fake()->name(),
                    'password' => Hash::make('123123')
                ]
            );

            // Create a resume for the applicant
            $resume = Resume::create([
                'id' => Str::uuid(),
                'filename' => $jobApplication['resume']['filename'],
                'fileUri' => $jobApplication['resume']['fileUri'],
                'contactDetails' => $jobApplication['resume']['contactDetails'],
                'summary' => $jobApplication['resume']['summary'],
                'skills' => $jobApplication['resume']['skills'],
                'experience' => $jobApplication['resume']['experience'],
                'education' => $jobApplication['resume']['education'],
                'userId' => $applicant->id
            ]);

            // Get a random job vacancy
            $jobVacancy = JobVacancy::inRandomOrder()->first();

            if ($jobVacancy) {
                // Create the job application
                JobApplication::create([
                    'id' => Str::uuid(),
                    'status' => $jobApplication['status'],
                    'aiGeneratedScore' => $jobApplication['aiGeneratedScore'],
                    'aiGeneratedFeedback' => $jobApplication['aiGeneratedFeedback'],
                    'jobId' => $jobVacancy->id,
                    'resumeId' => $resume->id,
                    'userId' => $applicant->id
                ]);
            }
        }
    }
}
