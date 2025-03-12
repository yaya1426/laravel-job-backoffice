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
        $companies = json_decode(file_get_contents(database_path('data/job_data.json')), true)['companies'];
        $company = $this->faker->randomElement($companies);
        
        return [
            'id' => Str::uuid(),
            'name' => $company['name'],
            'address' => $this->faker->address,
            'industry' => $company['industry'],
            'website' => 'https://www.' . Str::slug($company['name']) . '.com',
            'ownerId' => null,  // To be set dynamically in the seeder
        ];
    }
}
