<?php

namespace Psi\FlexAdmin\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Psi\FlexAdmin\Tests\Models\Property;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'type' => $this->faker->randomElement(Property::PROPERTY_TYPES),
            'options' => [
                'color' => $this->faker->randomElement(Property::PROPERTY_COLORS),
            ],
        ];
    }
}
