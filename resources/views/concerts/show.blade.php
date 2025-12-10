<x-guest-layout>
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-8">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $concert->title }}</h1>
                        <p class="mt-2 text-xl text-gray-600">📍 {{ $concert->location }}</p>
                        <p class="text-gray-500">{{ $concert->date }}</p>
                    </div>
                    <div class="text-right">
                        <span class="block text-4xl font-extrabold text-indigo-600">${{ $concert->price }}</span>
                        <span class="text-sm text-gray-500">por entrada</span>
                    </div>
                </div>

                <hr class="my-8 border-gray-200">

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg font-medium text-gray-900">Entradas Disponibles:</p>
                        <p class="text-3xl font-bold {{ $concert->available_tickets_count > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $concert->available_tickets_count }} / {{ $concert->total_tickets }}
                        </p>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                            <strong class="font-bold">¡Genial!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <strong class="font-bold">Ups!</strong>
                            <span class="block sm:inline">{{ $errors->first() }}</span>
                        </div>
                    @endif

                    @if($concert->available_tickets_count > 0)
                        <form action="{{ route('orders.store', $concert->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-8 rounded-full shadow-lg transform transition hover:scale-105 text-xl">
                                🎟️ ¡Comprar Entrada!
                            </button>
                        </form>
                    @else
                        <button disabled class="bg-gray-400 text-white font-bold py-4 px-8 rounded-full cursor-not-allowed">
                            🚫 Agotado
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
