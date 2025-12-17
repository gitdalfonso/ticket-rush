<x-layout title="Próximos Eventos">

    <div class="relative bg-gray-900 overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-gray-900 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">Tu música favorita</span>
                            <span class="block text-indigo-500 xl:inline">al alcance de un clic.</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-400 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Compra entradas oficiales sin filas virtuales eternas. Tecnología segura y rápida.
                        </p>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h2 class="text-3xl font-extrabold text-gray-900 mb-8">Próximos Eventos 🔥</h2>

        @if($concerts->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">No hay conciertos programados por ahora.</p>
            </div>
        @else
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach($concerts as $concert)
                    <div class="flex flex-col bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 transform hover:-translate-y-1">
                        <div class="h-48 bg-gradient-to-r from-purple-500 to-indigo-600 flex items-center justify-center">
                            <span class="text-white font-black text-3xl opacity-25 uppercase">{{ substr($concert->title, 0, 3) }}</span>
                        </div>

                        <div class="flex-1 p-6 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-indigo-600 text-xs font-bold uppercase tracking-wide">Concierto</span>
                                    <span class="text-gray-400 text-xs capitalize">{{ $concert->date->translatedFormat('d M Y') }}</span>
                                </div>

                                <div class="mb-2">
                                    @php
                                        // Contamos solo los que tienen status 'available'
                                        $available = $concert->tickets()->where('status', 'available')->count();
                                    @endphp

                                    @if($available === 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                    Agotado 🚫
                </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                    {{ $available }} disp. ✅
                </span>
                                    @endif
                                </div>

                                <a href="{{ route('concerts.show', $concert) }}" class="block mt-1">
                                    <p class="text-xl font-semibold text-gray-900 leading-tight">{{ $concert->title }}</p>
                                    <p class="mt-1 text-sm text-gray-500 flex items-center">
                                        <span class="mr-1">📍</span> {{ $concert->location }}
                                    </p>
                                </a>
                            </div>

                            <div class="mt-6 flex items-center justify-between">
                                <span class="text-2xl font-bold text-gray-900">${{ intval($concert->price) }}</span>
                                <a href="{{ route('concerts.show', $concert) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-indigo-700 transition shadow-md">
                                    Ver Entradas
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layout>
