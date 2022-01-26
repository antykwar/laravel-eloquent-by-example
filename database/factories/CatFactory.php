<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use JsonException;

class CatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     * @throws JsonException
     */
    public function definition()
    {
        return [
            'info' => [
                'name' => $this->faker->firstName(),
                'long-hair' => $this->faker->boolean(),
            ],
        ];
    }
}
