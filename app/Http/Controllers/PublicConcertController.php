<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use Illuminate\Http\Request;

class PublicConcertController extends Controller
{
    public function index()
    {
        // Listado para la Home Page (solo futuros)
        $concerts = Concert::where('date', '>=', now())
            ->orderBy('date')
            ->get();

        return view('welcome', compact('concerts'));
    }

    public function show(Concert $concert)
    {
        // Laravel ya busca automáticamente por el 'slug' gracias al cambio en el modelo
        return view('concerts.show', compact('concert'));
    }
}
