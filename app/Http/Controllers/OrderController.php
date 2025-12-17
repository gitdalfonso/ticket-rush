<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use App\Models\Order;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketPurchased;

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

                    Mail::to(Auth::user())->send(new TicketPurchased($order));

                    // CAMBIO: Redirección con mensaje verde de éxito
                    return back()->with('success', "¡Entrada comprada con éxito! Tu código es: {$ticket->code}");
                });
            });

        } catch (LockTimeoutException $e) {
            return back()->withErrors(['message' => 'El servidor está muy ocupado, intenta de nuevo.']);
        }
    }

    public function index()
    {
        // Traemos las órdenes del usuario con los datos del concierto y el ticket
        $orders = Order::with(['concert', 'tickets'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }
}
