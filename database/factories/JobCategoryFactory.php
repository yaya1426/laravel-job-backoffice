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
        return [
            'id' => Str::uuid(),
            'name' => $this->faker->unique()->randomElement([
                'Web Development',
                'Backend',
                'Frontend',
                'Data Science',
                'DevOps'
            ]),
        ];
    }
}
