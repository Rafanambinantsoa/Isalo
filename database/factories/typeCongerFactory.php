<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\typeConger>
 */
class typeCongerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $chiffres = [10, 30, 90];
        return [
            'libelle' => fake()->word(),
            'description' => fake()->sentence(),
            'duree' => array_rand($chiffres) ,
        ];
    }
}
