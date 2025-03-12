<?php

namespace Database\Factories;

use App\Models\JobCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class JobCategoryFactory extends Factory
{
    protected $model = JobCategory::class;

    public function definition()
    {
        $categories = json_decode(file_get_contents(database_path('data/job_data.json')), true)['jobCategories'];
        
        return [
            'id' => Str::uuid(),
            'name' => $this->faker->unique()->randomElement($categories),
        ];
    }
}
