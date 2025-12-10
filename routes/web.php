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
    ->middleware('auth')
    ->name('orders.store');

require __DIR__.'/auth.php';
