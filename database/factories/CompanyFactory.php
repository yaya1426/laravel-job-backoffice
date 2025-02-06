<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition()
    {
        return [
            'id' => Str::uuid(),
            'name' => $this->faker->company,
            'address' => $this->faker->address,
            'industry' => $this->faker->randomElement(['Technology', 'Finance', 'Healthcare', 'Manufacturing', 'Retail']),
            'website' => $this->faker->url,
            'ownerId' => null,  // To be set dynamically in the seeder
        ];
    }
}
