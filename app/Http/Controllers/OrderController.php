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
        // 1. Validar login real
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $userId = Auth::id();

        $concert = Concert::findOrFail($concertId);

        // 2. El mismo Bloqueo Atómico (No tocamos la lógica, es perfecta)
        $lock = Cache::lock('concert_sale_' . $concertId, 10);

        try {
            return $lock->block(5, function () use ($concert, $userId) {

                return DB::transaction(function () use ($concert, $userId) {

                    $ticket = $concert->tickets()
                        ->where('status', 'available')
                        ->lockForUpdate()
                        ->first();

                    if (!$ticket) {
                        // CAMBIO: En lugar de JSON, devolvemos al usuario atrás con un error rojo
                        return back()->withErrors(['message' => '¡Lo sentimos! Se acaban de agotar las entradas.']);
                    }

                    $order = Order::create([
                        'user_id' => $userId,
                        'concert_id' => $concert->id,
                        'status' => 'paid',
                    ]);

                    $ticket->update([
                        'status' => 'sold',
                        'order_id' => $order->id,
                    ]);

                    // CAMBIO: Redirección con mensaje verde de éxito
                    return back()->with('success', "¡Entrada comprada con éxito! Tu código es: {$ticket->code}");
                });
            });

        } catch (\Illuminate\Contracts\Cache\LockTimeoutException $e) {
            return back()->withErrors(['message' => 'El servidor está muy ocupado, intenta de nuevo.']);
        }
    }
}
