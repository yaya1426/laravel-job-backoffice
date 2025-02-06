<?php

namespace Database\Factories;

use App\Models\Resume;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ResumeFactory extends Factory
{
    protected $model = Resume::class;

    public function definition()
    {
        return [
            'id' => Str::uuid(),
            'filename' => $this->faker->word . '.pdf',
            'fileUri' => $this->faker->url,
            'contactDetails' => $this->faker->phoneNumber,
            'summary' => $this->faker->sentence,
            'skills' => implode(', ', $this->faker->words(5)),
            'experience' => $this->faker->paragraph(2),
            'education' => $this->faker->sentence,
            'userId' => null,  // Set dynamically
        ];
    }
}
