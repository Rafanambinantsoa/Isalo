<?php

namespace Database\Factories;

use App\Models\Paiment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\fournisseur>
 */
class FournisseurFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $paiment = Paiment::all()->random();

        return [
            'nom' => fake()->word(),
            'adresse' => fake()->address(),
            'contact' => fake()->phoneNumber(),
            'paiment_id' => $paiment->id,
        ];

    }
}
