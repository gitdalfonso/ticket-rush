<x-layout title="Mis Entradas">

    <div class="bg-gray-900 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Mis Entradas 🎟️</h1>
            <p class="mt-2 text-gray-400">Gestiona tus próximos eventos y accesos.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 pb-12">

        @if($orders->isEmpty())
            <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                <div class="mx-auto h-24 w-24 text-gray-300 mb-4">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">No tienes entradas aún</h3>
                <p class="mt-2 text-gray-500 max-w-sm mx-auto">Parece que no has comprado tickets para ningún evento. ¡No te quedes fuera!</p>
                <div class="mt-6">
                    <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                        Ver Conciertos Disponibles
                    </a>
                </div>
            </div>
        @else
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach($orders as $order)
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden transform hover:-translate-y-1 transition duration-300 flex flex-col">

                        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4 flex justify-between items-center">
                            <span class="text-white text-xs font-bold uppercase tracking-widest border border-white/30 rounded px-2 py-0.5">
                                Orden #{{ $order->id }}
                            </span>
                            <span class="text-white/80 text-xs capitalize">
                                {{ $order->created_at->translatedFormat('d M Y') }}
                            </span>
                        </div>

                        <div class="p-6 flex-1">
                            <a href="{{ route('concerts.show', $order->concert) }}" class="hover:underline hover:text-indigo-600 transition">
                                <h3 class="text-xl font-bold text-gray-900 leading-tight mb-1">{{ $order->concert->title }}</h3>
                            </a>

                            <div class="flex items-center text-gray-500 text-sm mb-6">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $order->concert->location }}
                            </div>

                            <div class="bg-gray-50 rounded-xl p-6 border-2 border-dashed border-gray-200 relative flex flex-col items-center justify-center">
                                <div class="absolute -left-2 top-1/2 w-4 h-4 bg-white rounded-full -mt-2 border-r border-gray-200"></div>
                                <div class="absolute -right-2 top-1/2 w-4 h-4 bg-white rounded-full -mt-2 border-l border-gray-200"></div>

                                <p class="text-xs text-center text-gray-400 uppercase tracking-widest mb-4">Escanea en la entrada</p>

                                <div class="p-2 bg-white border rounded shadow-sm">
                                    {!! QrCode::format('svg')
                                        ->size(150)
                                        ->color(0,0,0)
                                        ->backgroundColor(255,255,255)
                                        ->margin(2)
                                        ->generate($order->tickets->first()->code ?? 'ERROR')
                                    !!}
                                </div>

                                <p class="text-sm font-mono font-bold text-center text-gray-600 tracking-widest mt-4 select-all">
                                    {{ $order->tickets->first()->code ?? 'PROCESANDO' }}
                                </p>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-3 border-t border-gray-100 flex justify-between items-center mt-auto">
                            <div class="flex items-center text-green-600 text-sm font-bold">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Pagado
                            </div>
                            <span class="text-gray-900 font-bold">${{ intval($order->concert->price) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</x-layout>
