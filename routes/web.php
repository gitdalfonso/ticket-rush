<?php

use App\Http\Controllers\ConcertController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/concerts/{id}', [ConcertController::class, 'show'])->name('concerts.show');

Route::post('/concerts/{id}/orders', [OrderController::class, 'store'])
    ->middleware(['auth', 'throttle:3,1'])
    ->name('orders.store');

Route::get('/my-tickets', [App\Http\Controllers\OrderController::class, 'index'])
    ->middleware('auth')
    ->name('orders.index');

Route::get('/mail-preview', function () {
    // Buscamos una orden que tenga al menos un ticket asociado (has('tickets'))
    // y cargamos la relación de una vez (with('tickets'))
    $order = App\Models\Order::with('tickets')->has('tickets')->latest()->first();

    if(!$order) {
        return "No hay ninguna orden válida con tickets. Ve y compra una entrada primero.";
    }

    return new App\Mail\TicketPurchased($order);
});

require __DIR__.'/auth.php';
