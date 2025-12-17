<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketPurchased extends Mailable
{
    use Queueable, SerializesModels;

    // Inyectamos la orden en el constructor
    public function __construct(
        public Order $order
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Tu entrada para TicketRush está aquí! 🎟️',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
