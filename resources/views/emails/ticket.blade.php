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

    <div style="text-align: center; margin: 30px 0;">
        <p style="margin-bottom: 10px; color: #6b7280; font-size: 12px; text-transform: uppercase;">Código de Acceso QR</p>
        <div style="display: inline-block; padding: 15px; background: #fff; border: 2px dashed #d1d5db; border-radius: 8px;">
            {!! QrCode::format('svg')
                ->size(150)
                ->color(0, 0, 0)          // Color del código: Negro puro
                ->backgroundColor(255, 255, 255) // Fondo: Blanco puro (esto es clave)
                ->margin(2)               // Margen blanco alrededor para que el lector respire
                ->generate($order->tickets->first()->code ?? 'ERROR')
            !!}
        </div>
        <p style="margin-top: 10px; font-family: monospace; font-weight: bold; color: #374151;">
            {{ $order->tickets->first()->code }}
        </p>
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
