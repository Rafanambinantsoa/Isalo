<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'matricule' => 'admin',
            'nom' => 'admin',
            'prenom' => 'admin',
            'date_naiss' => '2020-01-01',
            'num_cin' => 'admin',
            'email' => 'admin',
            'contact' => 'admin',
            'situation_mat' => 'admin',
            'nombre_enf' => 'admin',
            'date_embauche' => '2020-01-01',
            'numero_cnaps' => 'admin',
            'numero_omsi' => 'admin',
            'banque' => 'admin',
            'num_compte_bancaire' => 'admin',
            'salaires_brut' => 10000,
            'photo' => 'admin',
            'poste_id' => 1,
            'is_admin' => 1,
            'password' => Hash::make('admin'),

        ]);
    }
}
