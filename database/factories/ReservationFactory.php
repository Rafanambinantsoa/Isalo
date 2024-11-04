<?php

namespace Database\Factories;

use App\Models\Chambre;
use App\Models\ClientToiles;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $chambres = Chambre::inRandomOrder()->first();
        $clients = ClientToiles::inrandomOrder()->first();

        // LA hausse saison et la basse saison
        $random = random_int(0, 1);

        return [
            'Chambre_id' => $chambres->id,
            'Client_id' => $clients->id,
            'date_arrive' => fake()->date(),
            'date_depart' => fake()->date(),
            'is_avance_paid' => random_int(0, 1),
            'avance_requise' => $random == 1 ? ($chambres->prix_nuitee * 20) / 100 : ($chambres->prix_nuitee * 50) / 100,
            'prix_total' => $chambres->prix_nuitee,
        ];
    }
}
