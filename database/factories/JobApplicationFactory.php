<?php

namespace Database\Factories;

use App\Models\JobApplication;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class JobApplicationFactory extends Factory
{
    protected $model = JobApplication::class;

    public function definition()
    {
        return [
            'id' => Str::uuid(),
            'status' => $this->faker->randomElement(['applied', 'reviewed', 'rejected', 'hired']),
            'aiGeneratedScore' => $this->faker->randomFloat(1, 0, 10),
            'aiGeneratedFeedback' => $this->faker->sentence,
            'jobId' => null,  // Set dynamically
            'userId' => null,  // Set dynamically
            'resumeId' => null,  // Set dynamically

        ];
    }
}
