<div class="space-y-6">
    <flux:header>
        <flux:heading size="xl" weight="semibold">
            {{ __('Detalles del Producto') }}
        </flux:heading>
    </flux:header>
    <x-card class="overflow-hidden">
        <!-- Imagen -->
        <img
            src="{{ asset('storage/' . $producto->imagen) }}"
            class="w-full h-64 object-cover"
            alt="Imagen del producto"
        />
        <!-- Contenido -->
        <div class="p-6 space-y-4">

            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-semibold text-zinc-900 dark:text-white">
                    {{ $producto->nombre }}
                </h2>

                <!-- Estado (badge) -->
                @if ($producto->disponible)
                    <span class="px-3 py-1 text-xs rounded bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                        Disponible
                    </span>
                @else
                    <span class="px-3 py-1 text-xs rounded bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300">
                        No disponible
                    </span>
                @endif

            </div>

            <p class="text-zinc-700 dark:text-zinc-300 leading-relaxed">
                {{ $producto->descripcion }}
            </p>

            <h3 class="text-xl font-semibold text-emerald-600 dark:text-emerald-400">
                ${{ number_format($producto->precio, 0, ',', '.') }}
            </h3>

        </div>

        <!-- Footer -->
        <div class="flex justify-between items-center px-6 pb-6">

            <a
                href="{{ route('admin.productos.index') }}"
                wire:navigate
                class="inline-flex items-center gap-2 text-zinc-600 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white transition"
            >
                <i class="fa-solid fa-arrow-left"></i>
                Volver
            </a>

            <div class="flex items-center gap-3">

                <!-- Botón Editar -->
                <a
                    href="{{ route('admin.productos.edit', $producto->id) }}"
                    wire:navigate
                    class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-700 transition shadow-sm"
                >
                    Editar
                </a>

                <!-- Botón Activar/Desactivar -->
                <button
                    wire:click="toggleDisponibilidad"
                    class="px-4 py-2 rounded-lg text-white text-sm shadow-sm
                        {{ $producto->disponible ? 'bg-red-600 hover:bg-red-700' : 'bg-emerald-600 hover:bg-emerald-700' }}"
                >
                    {{ $producto->disponible ? 'Marcar No Disponible' : 'Marcar Disponible' }}
                </button>

            </div>

        </div>
    </x-card>
    
</div>
