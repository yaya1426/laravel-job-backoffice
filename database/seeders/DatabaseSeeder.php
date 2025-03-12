<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\JobVacancy;
use App\Models\JobCategory;
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
        // Load job data
        $jobData = json_decode(file_get_contents(database_path('data/job_data.json')), true);

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

        // Seed job categories
        foreach ($jobData['jobCategories'] as $categoryName) {
            JobCategory::firstOrCreate(
                ['name' => $categoryName],
                ['id' => Str::uuid()]
            );
        }

        // Create company owners (one for each company in our JSON data)
        $owners = collect();
        foreach ($jobData['companies'] as $companyData) {
            $owner = User::factory()->create([
                'role' => 'company-owner',
                'name' => 'Owner of ' . $companyData['name'],
            ]);
            $owners->push($owner);
        }

        // Create companies and assign to owners
        $owners->each(function ($owner, $index) use ($jobData) {
            $company = Company::factory()->create([
                'name' => $jobData['companies'][$index]['name'],
                'industry' => $jobData['companies'][$index]['industry'],
                'website' => 'https://www.' . Str::slug($jobData['companies'][$index]['name']) . '.com',
                'ownerId' => $owner->id,
            ]);

            // Create 5 job vacancies for each company
            $categories = JobCategory::all();
            JobVacancy::factory(5)->create([
                'companyId' => $company->id,
                'categoryId' => fn() => $categories->random()->id,
            ]);
        });
    }
}
