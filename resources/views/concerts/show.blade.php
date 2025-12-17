<x-layout title="{{ $concert->title }}">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row">

            <div class="md:w-1/2 bg-gray-900 p-8 md:p-12 text-white flex flex-col justify-between relative overflow-hidden">
                <div class="absolute inset-0 bg-indigo-900 opacity-20"></div>

                <div class="relative z-10">
                    <p class="text-indigo-400 font-bold tracking-wider uppercase text-sm mb-2">
                        {{ $concert->date->translatedFormat('l d \d\e F, Y') }} • {{ $concert->date->format('H:i') }} hs
                    </p>
                    <h1 class="text-4xl md:text-5xl font-extrabold leading-tight mb-4 shadow-sm">{{ $concert->title }}</h1>

                    <div class="flex items-center text-gray-300 mb-6 bg-gray-800/50 p-3 rounded-lg w-fit">
                        <span class="font-medium">📍 {{ $concert->location }}</span>
                    </div>
                </div>

                <div class="relative z-10 mt-8">
                    <p class="text-sm text-gray-400 uppercase tracking-widest">Precio Oficial</p>
                    <div class="flex items-baseline">
                        <p class="text-6xl font-bold text-white">${{ intval($concert->price) }}</p>
                    </div>
                </div>
            </div>

            <div class="md:w-1/2 p-8 md:p-12 flex flex-col justify-center bg-gray-50">

                @php
                    // Contamos los disponibles reales (status = 'available')
                    $available = $concert->tickets()->where('status', 'available')->count();

                    // Calculamos los vendidos (Total - Disponibles)
                    $sold = $concert->total_tickets - $available;

                    // Porcentaje para la barra visual
                    $percentage = ($concert->total_tickets > 0)
                        ? ($sold / $concert->total_tickets) * 100
                        : 0;

                    // Lógica de Agotado (Solo si disponibles es 0 o menos)
                    $isSoldOut = $available <= 0;

                    // Colores
                    $barColor = $percentage < 50 ? 'bg-green-500' : ($percentage < 90 ? 'bg-yellow-500' : 'bg-red-500');
                    $textColor = $percentage < 50 ? 'text-green-600' : ($percentage < 90 ? 'text-yellow-600' : 'text-red-600');
                @endphp

                <div class="mb-10">
                    <div class="flex justify-between text-sm font-bold text-gray-600 mb-2">
                        <span>Disponibilidad</span>
                        <span class="{{ $textColor }}">{{ $available }} restantes</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden shadow-inner">
                        <div class="{{ $barColor }} h-3 rounded-full transition-all duration-1000 ease-out" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-6 bg-green-100 border-l-4 border-green-500 p-4 rounded text-green-800 shadow-sm">
                        <p class="font-bold">¡Entrada asegurada!</p>
                        <p class="text-sm">{{ session('success') }}</p>
                        <a href="{{ route('orders.index') }}" class="underline mt-2 block text-sm font-bold">Ver mi entrada ➔</a>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded text-red-700 shadow-sm">
                        <ul class="list-disc ml-5 text-sm mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($isSoldOut)
                    <div class="text-center">
                        <button disabled class="w-full bg-gray-200 text-gray-400 font-bold py-5 rounded-xl cursor-not-allowed text-xl border-2 border-dashed border-gray-300">
                            🚫 ENTRADAS AGOTADAS
                        </button>
                    </div>
                @else
                    <form action="{{ route('orders.store', $concert->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-5 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-200 text-xl flex justify-center items-center">
                            <span>Comprar Entrada</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-layout>
