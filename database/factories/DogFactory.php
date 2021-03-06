<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->firstName(),
            'age' => $this->faker->numberBetween(1, 12),
        ];
    }
}
