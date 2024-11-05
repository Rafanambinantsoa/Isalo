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
        Schema::create('paiment_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->nullable()->constrained('reservations')->onDelete('set null'); // Référence à la réservation
            $table->foreignId('client_id')->nullable()->constrained('client_toiles')->onDelete('set null');
            $table->decimal('montant_restant', 10, 2);
            $table->decimal('montant', 10, 2);
            $table->string('type')->default('reservation');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiment_reservations');
    }
};
