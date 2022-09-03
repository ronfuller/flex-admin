<?php

namespace Psi\FlexAdmin\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Psi\FlexAdmin\Tests\Models\Unit;

class UnitFactory extends Factory
{
    protected $model = Unit::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status' => $this->faker->randomElement(['Pending', 'Available', 'Unavailable']),
            'title' => $this->faker->company().' Unit #'.$this->faker->numberBetween(1, 100),
            'tagLine' => $this->faker->sentence(),
            'rent' => $this->faker->numberBetween(1000, 2500),
            'size' => $this->faker->randomElement([450, 600, 850, 1200]),
            'beds' => $this->faker->randomElement(['Studio', '1 Bed', '2 Beds', '3 Beds']),
            'baths' => $this->faker->randomElement(['1 Bath', '1.5 Bath', '2 Baths']),
            'pets' => 'Pets OK',

        ];
    }
}
