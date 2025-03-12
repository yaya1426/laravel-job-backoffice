<?php

namespace Database\Factories;

use App\Models\JobVacancy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class JobVacancyFactory extends Factory
{
    protected $model = JobVacancy::class;

    public function definition()
    {
        $jobData = json_decode(file_get_contents(database_path('data/job_data.json')), true);
        $title = $this->faker->randomElement($jobData['jobTitles']);
        
        // Determine job type and technologies based on title
        $jobType = Str::contains(Str::lower($title), 'frontend') ? 'frontend' :
                  (Str::contains(Str::lower($title), 'backend') ? 'backend' : 'fullstack');
        
        // Get relevant technologies based on job type
        $technologies = [];
        if ($jobType === 'fullstack') {
            $technologies = array_merge(
                $this->faker->randomElements($jobData['technologies']['frontend'], 2),
                $this->faker->randomElements($jobData['technologies']['backend'], 2),
                $this->faker->randomElements($jobData['technologies']['databases'], 1)
            );
        } else {
            $technologies = array_merge(
                $this->faker->randomElements($jobData['technologies'][$jobType], 3),
                $this->faker->randomElements($jobData['technologies']['databases'], 1)
            );
        }

        // Get salary range based on level
        $level = Str::contains(Str::lower($title), 'senior') ? 'senior' :
                (Str::contains(Str::lower($title), 'lead') ? 'lead' : 'mid');
        $salaryRange = $jobData['salaryRanges'][$level];
        
        // Generate description
        $description = str_replace(
            '{technologies}',
            implode(', ', $technologies),
            $jobData['jobDescriptions'][$jobType]
        );

        return [
            'id' => Str::uuid(),
            'title' => $title,
            'description' => $description,
            'location' => $this->faker->randomElement($jobData['locations']),
            'type' => $this->faker->randomElement(['Full-time', 'Contract', 'Remote', 'Hybrid']),
            'salary' => $this->faker->numberBetween($salaryRange['min'], $salaryRange['max']),
            'companyId' => null,  // Set dynamically
            'categoryId' => null,  // Set dynamically
            'required_skills' => implode(', ', $technologies)
        ];
    }
}

