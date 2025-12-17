<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TicketRush - {{ $concert->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Un pequeño efecto al hacer clic en el botón */
        .btn-rush:active { transform: scale(0.95); }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">

<nav class="bg-white shadow sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <span class="text-2xl font-black text-indigo-600 tracking-tighter">TicketRush ⚡</span>
            </div>
            <div class="flex items-center">
                @auth
                    <span class="text-gray-600 mr-4 text-sm font-medium">Hola, {{ Auth::user()->name }}</span>
                @else
                    <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:text-indigo-500">Iniciar Sesión</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<div class="relative bg-gray-900 pb-48 overflow-hidden">
    <div class="absolute inset-0">
        <img class="w-full h-full object-cover opacity-40" src="https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80" alt="Concert crowd">
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40"></div>
    </div>
    <div class="relative max-w-7xl mx-auto py-24 px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl uppercase shadow-sm">
            {{ $concert->title }}
        </h1>
        <p class="mt-4 text-xl text-gray-300 max-w-3xl">
            Una experiencia única. Sonido de alta fidelidad. Luces estroboscópicas. No te lo pierdas.
        </p>
    </div>
</div>

<div class="relative -mt-32 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    <div class="bg-white rounded-xl shadow-2xl overflow-hidden lg:flex">

        <div class="p-8 lg:p-12 lg:w-2/3">
            <div class="flex items-start mb-8">
                <div class="bg-indigo-100 rounded-lg p-3 text-indigo-600 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Ubicación</h3>
                    <p class="text-gray-600">{{ $concert->location }}</p>
                    <p class="text-gray-500 text-sm mt-1">{{ \Carbon\Carbon::parse($concert->date)->format('d F Y, h:i A') }}</p>
                </div>
            </div>

            <hr class="border-gray-100 my-6">

            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r">
                    <div class="flex">
                        <div class="flex-shrink-0 text-green-500">✅</div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700 font-bold">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r">
                    <div class="flex">
                        <div class="flex-shrink-0 text-red-500">⚠️</div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-bold">Ups, algo salió mal:</p>
                            <p class="text-sm text-red-600">{{ $errors->first() }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <p class="text-gray-500 text-sm">
                * Las entradas son limitadas y se asignan en tiempo real. Máximo 1 por transacción.
            </p>
        </div>

        <div class="bg-gray-50 p-8 lg:p-12 lg:w-1/3 border-l border-gray-200 flex flex-col justify-center">
            <div class="text-center mb-8">
                <span class="block text-gray-500 text-xs font-bold uppercase tracking-wider">Precio por entrada</span>
                <span class="block text-5xl font-extrabold text-gray-900 tracking-tight">${{ intval($concert->price) }}<span class="text-2xl text-gray-500">.00</span></span>
            </div>

            <div class="mb-8">
                <div class="flex justify-between text-sm font-medium mb-2">
                    <span class="text-gray-700">Disponibilidad</span>
                    <span class="{{ $concert->available_tickets_count > 10 ? 'text-indigo-600' : 'text-red-600' }}">
                            {{ $concert->available_tickets_count }} / {{ $concert->total_tickets }}
                        </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    @php
                        $percentage = ($concert->available_tickets_count / $concert->total_tickets) * 100;
                        // Color rojo si queda menos del 20%, azul si hay más
                        $color = $percentage < 20 ? 'bg-red-500' : 'bg-indigo-600';
                    @endphp
                    <div class="{{ $color }} h-3 rounded-full transition-all duration-1000 ease-out" style="width: {{ $percentage }}%"></div>
                </div>
                @if($concert->available_tickets_count < 5 && $concert->available_tickets_count > 0)
                    <p class="text-xs text-red-500 mt-2 font-bold animate-pulse text-center">¡Casi agotado! 🔥</p>
                @endif
            </div>

            @if($concert->available_tickets_count > 0)
                <form action="{{ route('orders.store', $concert->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-rush w-full bg-indigo-600 border border-transparent rounded-xl py-4 px-6 flex items-center justify-center text-lg font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-xl transition-all">
                        🎟️ Comprar Ahora
                    </button>
                </form>
                <p class="mt-4 text-center text-xs text-gray-400">Transacción segura SSL • TicketRush Protect™</p>
            @else
                <button disabled class="w-full bg-gray-300 border border-transparent rounded-xl py-4 px-6 flex items-center justify-center text-lg font-bold text-gray-500 cursor-not-allowed">
                    🚫 Agotado
                </button>
                <p class="mt-4 text-center text-xs text-gray-400">Suscríbete para la próxima fecha</p>
            @endif
        </div>
    </div>
</div>

</body>
</html>
