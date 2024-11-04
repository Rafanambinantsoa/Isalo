<?php

namespace Database\Factories;

use App\Models\Categorie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produit>
 */
class ProduitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = Categorie::inRandomOrder()->first();

        return [
            'nom' => fake()->text(10),
            'categorie_id' => $categories->id,
            'quantite' => fake()->numberBetween(1, 10),
            'prix' => fake()->numberBetween(100, 1000),
        ];
    }
}
