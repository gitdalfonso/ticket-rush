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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('concert_id')->constrained()->onDelete('cascade');
            // El ticket puede no tener orden aún (si no se ha vendido)
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');

            $table->string('code')->unique()->nullable(); // El código único para el QR

            // status: available (libre), reserved (en carrito), sold (vendido)
            $table->enum('status', ['available', 'reserved', 'sold'])->default('available');

            $table->timestamps();

            // Índices para velocidad al buscar tickets libres (Muy importante para concurrencia)
            $table->index(['concert_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
