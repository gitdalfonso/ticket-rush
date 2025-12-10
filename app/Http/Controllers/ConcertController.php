<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use Illuminate\Http\Request;

class ConcertController extends Controller
{
    public function show($id)
    {
        // Buscamos el concierto por ID.
        // ADEMÁS: Le pedimos a la BD que cuente cuántos tickets tienen status 'available'.
        // Esto crea una variable mágica 'available_tickets_count' en el modelo.
        $concert = Concert::withCount(['tickets as available_tickets_count' => function ($query) {
            $query->where('status', 'available');
        }])->findOrFail($id);

        return view('concerts.show', compact('concert'));
    }
}
