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
        Schema::create('chambres', function (Blueprint $table) {
            $table->id();
            $table->string('Numero_chambre');
            $table->unsignedBigInteger('id_categorie_chambre');
            $table->foreign('id_categorie_chambre')->references('id')->on('categorie_chambres');
            $table->integer('Nombre_lits');
            $table->string('Type_Lits');
            $table->bigInteger('Prix_Nuitée');
            $table->string('Etat_chambre');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chambres');
    }
};
