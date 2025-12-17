<?php

use App\Http\Controllers\ConcertController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicConcertController;
use Illuminate\Support\Facades\Route;

// Página de Inicio (Listado de Conciertos)
Route::get('/', [PublicConcertController::class, 'index'])->name('home');

// Mis Entradas (Protegido)
Route::get('/my-tickets', [OrderController::class, 'index'])
    ->middleware('auth')
    ->name('orders.index');

// Detalle del Concierto (URL Amigable)
// Nota: Usamos {concert} para que Laravel inyecte el modelo buscando por slug
Route::get('/concerts/{concert}', [PublicConcertController::class, 'show'])->name('concerts.show');

// Procesar Compra
Route::post('/concerts/{id}/orders', [OrderController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('orders.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/login', function () {
    return redirect()->route('filament.admin.auth.login');
})->name('login');

require __DIR__.'/auth.php';
