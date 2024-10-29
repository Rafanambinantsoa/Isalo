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
        Schema::create('approvisionnements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fournisseur_id');
            $table->foreign('fournisseur_id')->references('id')->on('fournisseurs');
            $table->date('date_approvisionnement')->default(now());

            $table->bigInteger('montant_approvisionnement');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvisionnements');
    }
};
