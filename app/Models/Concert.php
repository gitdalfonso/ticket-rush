<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Concert extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'date' => 'datetime', // Esto convierte el texto en un objeto Carbon automáticamente
    ];

    // Esta es la función que withCount('tickets') busca
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
