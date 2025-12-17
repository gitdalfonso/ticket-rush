<?php

namespace App\Filament\Resources\ConcertResource\Pages;

use App\Filament\Resources\ConcertResource;
use App\Models\Ticket;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditConcert extends EditRecord
{
    protected static string $resource = ConcertResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // Redirigir al índice después de guardar
    protected function getRedirectUrl(): string
    {
        return self::getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        // Obtenemos el concierto que acabamos de guardar
        $concert = $this->record;

        // Contamos cuántos tickets físicos existen realmente
        $currentTickets = $concert->tickets()->count();

        // Vemos si el admin aumentó la capacidad
        // (Capacidad Nueva - Tickets Reales)
        $missingTickets = $concert->total_tickets - $currentTickets;

        if ($missingTickets > 0) {
            // Generamos los tickets que faltan
            for ($i = 0; $i < $missingTickets; $i++) {
                Ticket::create([
                    'concert_id' => $concert->id,
                    'status' => 'available',
                    'code' => 'TICKET-' . strtoupper(Str::random(8)),
                ]);
            }

            // Enviamos una notificación visual al Admin
            Notification::make()
                ->title('Inventario Actualizado')
                ->body("Se han generado automáticamente {$missingTickets} nuevas entradas.")
                ->success()
                ->send();
        }
    }
}
