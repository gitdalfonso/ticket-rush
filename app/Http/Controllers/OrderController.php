<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderController extends Controller
{
    public function store($concertId)
    {
        // Validar que el usuario esté logueado
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $concert = Concert::findOrFail($concertId);
        try {
            DB::beginTransaction();

            // Buscar UN ticket disponible cualquiera
            // El problema: Si 2 usuarios leen esta línea al mismo tiempo,
            // ambos obtendrán el MISMO ticket.
            $ticket = $concert->tickets()
                ->where('status', 'available')
                ->first();

            // Si no hay tickets, rollback y error
            if (!$ticket) {
                DB::rollBack();
                return back()->withErrors(['message' => '¡Lo sentimos! Se acaban de agotar las entradas.']);
            }

            // Crear la Orden
            $order = Order::create([
                'user_id' => Auth::id(),
                'concert_id' => $concert->id,
                'status' => 'paid', // Simulamos que pagó al instante
            ]);

            // Marcar el ticket como VENDIDO y asignarlo a la orden
            $ticket->update([
                'status' => 'sold',
                'order_id' => $order->id,
            ]);

            DB::commit();

            // Éxito
            return back()->with('success', "¡Entrada comprada con éxito! Tu código es: {$ticket->code}");

        } catch (Exception | Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['message' => 'Ocurrió un error inesperado: ' . $e->getMessage()]);
        }
    }
}
