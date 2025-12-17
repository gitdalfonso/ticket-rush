<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TicketRush ⚡ - {{ $title ?? 'Inicio' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans antialiased flex flex-col min-h-screen">

<nav class="bg-white shadow sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-2xl font-black text-indigo-600 tracking-tighter hover:opacity-80">
                    TicketRush ⚡
                </a>
            </div>

            <div class="flex items-center space-x-6">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-indigo-600 font-medium transition text-sm">Inicio</a>

                @auth
                    @if(Auth::user()->email === 'admin@ticketrush.com')
                        <a href="/admin" class="text-xs font-bold bg-gray-900 text-white px-3 py-1 rounded uppercase hover:bg-gray-700 transition">
                            Admin
                        </a>
                    @endif

                    <a href="{{ route('orders.index') }}" class="text-gray-900 font-bold hover:underline text-sm">
                        Mis Entradas
                    </a>

                        <a href="{{ route('profile.edit') }}" class="text-gray-500 hover:text-indigo-600 text-sm font-medium ml-4">
                            Mi Perfil
                        </a>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-red-600 text-sm font-medium ml-2">
                            (Salir)
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-900 font-medium hover:text-indigo-600 text-sm">Entrar</a>
                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-3 py-1.5 rounded text-sm font-bold hover:bg-indigo-700 transition">
                        Registro
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<main class="flex-grow">
    {{ $slot }}
</main>

<footer class="bg-gray-900 text-white py-8 mt-auto">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <p class="text-gray-500 text-sm">© {{ date('Y') }} TicketRush Inc. Proyecto Demo.</p>
    </div>
</footer>

</body>
</html>
