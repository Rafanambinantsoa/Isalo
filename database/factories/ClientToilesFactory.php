<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClientToiles>
 */
class ClientToilesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => fake()->name(),
            'address' => fake()->address(),
            'cin' => fake()->numberBetween(10000000, 99999999),
            'preferences' => fake()->word(),
        ];
    }
}
