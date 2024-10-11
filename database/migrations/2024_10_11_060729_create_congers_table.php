<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('types_conges', function (Blueprint $table) {
            $table->id(); // Clé primaire
            $table->string('nom'); // Nom du type de congé
            $table->text('description')->nullable(); // Description du type de congé
            $table->integer('nombre_jours_max'); // Nombre maximum de jours permis
            $table->timestamps(); // Colonnes created_at et updated_at
        });

        Schema::create('congers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Référence à l'employé
            $table->foreignId('type_conge_id')->constrained('types_conges')->onDelete('cascade'); // Référence au type de congé
            $table->date('date_debut'); // Date de début du congé
            $table->date('date_fin'); // Date de fin du congé
            $table->integer('nombre_jours'); // Nombre de jours de congé
            $table->string('statut')->default('en attente'); // Statut du congé (ex. : approuvé, en attente, rejeté)
            $table->text('motif')->nullable(); // Motif du congé
            $table->text('commentaire_admin')->nullable(); // Commentaires de l'administrateur

            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('types_conges');
        Schema::dropIfExists('congers');
    }
};
