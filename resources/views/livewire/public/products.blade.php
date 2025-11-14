<div class="w-full">
    <!-- Encabezado -->
    <flux:header>
        <flux:heading size="xl" weight="semibold">
            {{ __('Menu Fastfood') }}
        </flux:heading>
    </flux:header>

    {{-- Barra de busqueda --}}
    <div class="max-w-md mx-auto mb-8">
        <x-input wire:model="search" type="text" placeholder="Buscar..." />
    </div>
    {{-- Lista de productos --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($productos as $producto)
            <x-card class="flex flex-col h-full p-0 overflow-hidden">

                <!-- Imagen -->
                <img 
                    src="{{ asset('storage/' . $producto->imagen) }}" 
                    alt="{{ $producto->nombre }}"
                    class="w-full h-44 object-cover"
                >

                <div class="p-4 flex flex-col h-full">
                    <h2 class="text-xl dark:text-white font-semibold mb-1">{{ $producto->nombre }}</h2>
                    <p class="text-neutral-600 dark:text-neutral-400 text-sm mb-3">{{ $producto->descripcion }}</p>

                    <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">
                        ${{ number_format($producto->precio, 0, ',', '.') }}
                    </p>

                    <x-button 
                        variant="primary" 
                        size="md"
                        class="mt-auto w-full"
                        wire:click="$dispatch('addToCart', { id: {{ $producto->id }} })"
                    >
                        Agregar al pedido
                    </x-button>
                </div>

            </x-card>
        @empty
            <p class="text-center text-neutral-500 w-full col-span-full">No hay productos disponibles.</p>
        @endforelse
    </div>
</div>
