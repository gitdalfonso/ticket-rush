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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // Quién compra
            $table->foreignId('concert_id')->constrained(); // Qué concierto
            // Estado: pending (reservado), paid (pagado), cancelled (expiró/falló)
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->string('payment_reference')->nullable(); // ID de la pasarela de pago
            $table->timestamps();

            // Un usuario no debería tener multiples ordenes pendientes para el mismo concierto (opcional, buena práctica)
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
