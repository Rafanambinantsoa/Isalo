<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Fournisseur;
use App\Models\Paiment;
use App\Models\Poste;
use App\Models\typeConger;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Categorie produit
        Categorie::factory(10)->create();

        //Factory paiments
        Paiment::factory(10)->create();

        //Factory type fournisseurs
        Fournisseur::factory(10)->create();

        typeConger::factory()->create([
            'nom' => 'Specifique',
            'libelle' => 'Specifique',
            'description' => 'Specifique',
            'duree' => 0,
        ]);
        typeConger::factory()->create([
            'nom' => 'Maternité',
            'libelle' => 'Maternité',
            'description' => 'Maternité',
            'duree' => 90,
        ]);
        typeConger::factory()->create([
            'nom' => 'Maladie',
            'libelle' => 'Maladie',
            'description' => 'Maladie',
            'duree' => 10,
        ]);
        typeConger::factory()->create([
            'nom' => 'Annuele',
            'libelle' => 'Annuele',
            'description' => 'Annuele',
            'duree' => 30,
        ]);
        $postes = Poste::factory(10)->create();
        User::factory(10)->create([
            'poste_id' => $postes->random()->id,
        ]);

        //Admin User
        User::factory()->create([
            'matricule' => 'admin',
            'nom' => 'admin',
            'prenom' => 'admin',
            'date_naiss' => '2020-01-01',
            'num_cin' => 'admin',
            'email' => 'admin@ex.com',
            'contact' => 'admin',
            'situation_mat' => 'admin',
            'nombre_enf' => 'admin',
            'date_embauche' => '2020-01-01',
            'numero_cnaps' => 'admin',
            'numero_omsi' => 'admin',
            'banque' => 'admin',
            'num_compte_bancaire' => 'admin',
            'salaires_brut' => 8000,
            'photo' => 'admin',
            'poste_id' => $postes->random()->id,
            'password' => bcrypt('admin'),
        ]);
    }
}
