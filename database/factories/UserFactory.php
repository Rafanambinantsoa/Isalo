<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'matricule' => Str::random(10),
            'nom' => fake()->name(),
            'prenom' => fake()->name(),
            'date_naiss' => fake()->date(),
            'num_cin' => Str::random(10),
            'email' => fake()->safeEmail(),
            'contact' => fake()->phoneNumber(),
            'situation_mat' => fake()->word(),
            'nombre_enf' => fake()->numberBetween(0, 10),
            'date_embauche' => fake()->date(),
            'numero_cnaps' => Str::random(10),
            'numero_omsi' => Str::random(10),
            'banque' => fake()->word(),
            'num_compte_bancaire' => Str::random(10),
            'salaires_brut' => fake()->numberBetween(1000, 10000),
            'photo' => fake()->imageUrl(),
            'is_employe' => fake()->boolean(),
            'poste_id' => random_int(0 ,10) ,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
