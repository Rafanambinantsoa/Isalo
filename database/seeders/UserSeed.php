<?php

namespace Database\Seeders;

use App\Models\Poste;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $postes =   Poste::factory(10)->create();
        User::factory(10)->create([
            'poste_id' => $postes->random()->id,
        ]);
    }
}
