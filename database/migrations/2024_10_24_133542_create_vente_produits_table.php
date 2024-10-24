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
        Schema::create('vente_produits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vente_id');
            $table->unsignedBigInteger('produit_id');

            $table->foreign('vente_id')->references('id')->on('ventes')->onDelete('cascade');
            $table->foreign('produit_id')->references('id')->on('produits')->onDelete('cascade');

            $table->integer('quantite');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vente_produits');
    }
};
