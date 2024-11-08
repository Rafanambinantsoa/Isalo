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
        Schema::create('historique_approvisionnements_toiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('approvi_id');
            $table->unsignedBigInteger('produit_id');
            $table->bigInteger('quantite');

            $table->foreign('approvi_id')->references('id')->on('approvisionnements')->onDelete('cascade');
            $table->foreign('produit_id')->references('id')->on('produits')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historique_approvisionnements_toiles');
    }
};
