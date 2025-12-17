<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class OrderController extends Controller
{
    public function store($concertId)
    {
        // Simulamos usuario logueado (ID 1 para la prueba)
        $userId = 1;

        $concert = Concert::findOrFail($concertId);

        // ======================================================
        // SOLUCIÓN SENIOR: REDIS ATOMIC LOCK
        // ======================================================
        // Creamos un "semáforo" llamado 'concert_sale_{id}'.
        // Solo 1 proceso puede pasar a la vez.

        $lock = Cache::lock('concert_sale_' . $concertId, 10);

        try {
            // block(5): Intenta entrar por 5 segundos. Si hay fila, espera.
            return $lock->block(5, function () use ($concert, $userId) {

                // --- ZONA SEGURA (Solo 1 a la vez) ---

                return DB::transaction(function () use ($concert, $userId) {

                    // Buscamos ticket y bloqueamos la fila en MySQL también (doble seguridad)
                    $ticket = $concert->tickets()
                        ->where('status', 'available')
                        ->lockForUpdate()
                        ->first();

                    if (!$ticket) {
                        // Ahora devolvemos 422 (Unprocessable Entity) explícito
                        return response()->json(['message' => '¡Agotado!'], 422);
                    }

                    // Creamos la orden
                    $order = Order::create([
                        'user_id' => $userId,
                        'concert_id' => $concert->id,
                        'status' => 'paid',
                    ]);

                    // Marcamos ticket como vendido
                    $ticket->update([
                        'status' => 'sold',
                        'order_id' => $order->id,
                    ]);

                    // Devolvemos 201 (Created)
                    return response()->json(['success' => "Ticket {$ticket->code} comprado."], 201);
                });
            });

        } catch (\Illuminate\Contracts\Cache\LockTimeoutException $e) {
            // Si esperó 5 segundos y no pudo entrar
            return response()->json(['message' => 'Servidor saturado, intenta de nuevo.'], 429);
        }
    }
}
