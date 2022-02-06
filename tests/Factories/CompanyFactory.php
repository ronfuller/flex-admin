<?php

namespace Psi\FlexAdmin\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Psi\FlexAdmin\Tests\Models\Company;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'type' => $this->faker->randomElement(['Small', 'Large', 'Enterprise']),
            'settings' => [
                'color' => $this->faker->randomElement(['blue', 'green', 'yellow', 'purple', 'orange']),
                'employees' => $this->faker->randomElement(['1000-5000', '100-1000', '10-100', 'Under 10']),
            ],
        ];
    }
}
