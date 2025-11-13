<div class="space-y-6">
    <!-- Encabezado -->
    <flux:header>
        <flux:heading size="xl" weight="semibold">
            {{ __('Gestión de Productos') }}
        </flux:heading>
    </flux:header>

    <!-- Simulación de Tabs de categorías -->
    <div class="flex flex-wrap justify-between items-center border-b border-zinc-200 dark:border-zinc-700 pb-3">
        <div class="flex flex-wrap gap-2">
            @foreach ($categorias as $categoria)
            <button
                wire:click="seleccionarCategoria({{ $categoria->id }})"
                class="px-4 py-2 rounded-lg text-sm font-medium transition 
                        {{ $categoriaSeleccionada == $categoria->id 
                            ? 'bg-emerald-600 text-white shadow-md' 
                            : 'bg-transparent text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700' }}"
            >
                {{ $categoria->nombre }}
            </button>
            @endforeach
        </div>
        <div>
            <a 
                href="{{ route('admin.productos.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition shadow-sm"
            >
            <i class="fa-solid fa-plus"></i>
                Nuevo producto
            </a>
        </div>
    </div>

    <!-- Grid de productos -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mt-6">
        @forelse ($productos as $producto)
            <x-card class="flex flex-col justify-between p-0 overflow-hidden">

                <a href="{{route('admin.productos.show', $producto->id)}}" wire:navigate="false">
                <!-- Imagen -->
                    <div class="p-0">
                        <img
                            src="{{ asset('storage/' . $producto->imagen) }}"
                            alt="{{ $producto->nombre }}"
                            class="object-cover w-full h-40"
                        />
                    </div>
                </a>

                <!-- Contenido -->
                <div class="flex-1 flex flex-col p-4">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $producto->nombre }}</h3>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 flex-grow">
                        {{ Str::limit($producto->descripcion, 100) }}
                    </p>
                </div>

                <!-- Footer -->
                <div class="flex justify-between items-center px-4 pb-4">
                    <span class="text-emerald-600 dark:text-emerald-400 font-semibold">
                        ${{ number_format($producto->precio, 0, ',', '.') }}
                    </span>

                    <div class="flex items-center gap-3">

                        <!-- Estado -->
                        @if ($producto->disponible)
                            <span class="text-xs px-2 py-1 rounded bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                                Disponible
                            </span>
                        @else
                            <span class="text-xs px-2 py-1 rounded bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300">
                                No disponible
                            </span>
                        @endif

                        <!-- Ícono Editar -->
                        <a 
                            href="{{ route('admin.productos.edit', $producto->id) }}"
                            class="text-zinc-500 hover:text-emerald-600 transition text-lg"
                            title="Editar producto"
                            wire:navigate="false"
                        >
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </div>
                </div>
            </x-card>
        @empty
            <div class="col-span-full text-center py-10">
                <p class="text-zinc-500 dark:text-zinc-400">
                    No hay productos en esta categoría.
                </p>
            </div>
        @endforelse
    </div>
</div>
