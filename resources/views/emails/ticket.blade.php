<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; background-color: #f3f4f6; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { text-align: center; border-bottom: 2px solid #e5e7eb; padding-bottom: 20px; margin-bottom: 20px; }
        .title { color: #4f46e5; font-size: 24px; font-weight: bold; }
        .details { margin-bottom: 30px; }
        .ticket-code { background: #f9fafb; border: 2px dashed #d1d5db; padding: 15px; text-align: center; font-family: monospace; font-size: 28px; font-weight: bold; color: #111827; letter-spacing: 4px; border-radius: 8px; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #6b7280; }
        .btn { display: inline-block; background-color: #4f46e5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 20px; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="title">TicketRush ⚡</div>
        <p>¡Gracias por tu compra, {{ $order->user->name }}!</p>
    </div>

    <div class="details">
        <p>Aquí tienes los detalles de tu evento:</p>
        <p><strong>Evento:</strong> {{ $order->concert->title }}</p>
        <p><strong>Lugar:</strong> {{ $order->concert->location }}</p>
        <p><strong>Fecha:</strong> {{ $order->concert->date }}</p>
        <p><strong>Precio:</strong> ${{ $order->concert->price }}</p>
    </div>

    <div class="ticket-code">
        {{ $order->tickets->first()->code }}
    </div>

    <div style="text-align: center;">
        <a href="{{ route('orders.index') }}" class="btn">Ver en mi Billetera</a>
    </div>

    <div class="footer">
        <p>Este código es único e intransferible.</p>
        <p>© {{ date('Y') }} TicketRush Inc.</p>
    </div>
</div>
</body>
</html>
