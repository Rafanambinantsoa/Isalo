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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Chambre_id');
            $table->foreign('Chambre_id')->references('id')->on('chambres')->onDelete('cascade');

            $table->unsignedBigInteger('Client_id');
            $table->foreign('Client_id')->references('id')->on('client_toiles')->onDelete('cascade');

            $table->date('date_arrive');
            $table->date('date_depart');

            $table->string('statut')->default('reserved');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
