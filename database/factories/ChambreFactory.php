<?php

namespace Database\Factories;

use App\Models\CategorieChambre;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chambre>
 */
class ChambreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $id_categorie_chambre = CategorieChambre::inRandomOrder()->first()->id;

        return [
            'Numero_chambre' => uniqid(),
            'id_categorie_chambre' => $id_categorie_chambre,
            'Nombre_lits' => fake()->numberBetween(1, 4),
            'Type_Lits' => fake()->word(),
            'Prix_Nuitée' => fake()->numberBetween(100, 500),
            'Etat_chambre' => fake()->word(),
        ];
    }
}
