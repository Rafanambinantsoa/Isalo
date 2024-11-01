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

        return [
            'Chambre_id' => $chambres->id,
            'Client_id' => $clients->id,
            'date_arrive' => fake()->date(),
            'date_depart' => fake()->date(),
        ];
    }
}
