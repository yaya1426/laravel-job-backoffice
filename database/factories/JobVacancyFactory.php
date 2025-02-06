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
        return [
            'id' => Str::uuid(),
            'title' => $this->faker->jobTitle,
            'description' => $this->faker->paragraph,
            'location' => $this->faker->city,
            'type' => $this->faker->randomElement(['full-time', 'part-time', 'remote']),
            'salary' => $this->faker->numberBetween(30000, 100000),
            'companyId' => null,  // Set dynamically
            'categoryId' => null,  // Set dynamically
        ];
    }
    
}

