<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Un ticket pertenece a un Concierto
    public function concert(): BelongsTo
    {
        return $this->belongsTo(Concert::class);
    }

    // Un ticket puede pertenecer a una Orden (cuando se compra)
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
