<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Una orden pertenece a un Usuario
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Una orden tiene muchos Tickets comprados
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    // Una orden es para UN concierto específico
    public function concert(): BelongsTo
    {
        return $this->belongsTo(Concert::class);
    }
}
