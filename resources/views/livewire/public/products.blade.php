<div class="w-full">
    {{-- BANNER DEL RESTAURANTE --}}
    <div class="relative w-full h-40 md:h-64 rounded-xl overflow-hidden mb-8 shadow-sm group">
        @if (!empty($globalSiteBanner))
            {{-- Banner Personalizado --}}
            <img src="{{ asset('storage/' . $globalSiteBanner) }}"
                class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
            <div class="absolute inset-0 bg-black/20"></div> {{-- Oscurecimiento suave --}}
        @else
            {{-- Banner por Defecto (Gradiente elegante) --}}
            <div class="w-full h-full bg-gradient-to-r from-emerald-600 to-teal-500 flex items-center justify-center">
                <div class="text-white/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-32" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72m-13.5 8.65h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                    </svg>
                </div>
            </div>
        @endif

        {{-- Texto Sobrepuesto (Opcional, nombre del negocio) --}}
        <div class="absolute bottom-0 left-0 w-full p-6 bg-gradient-to-t from-black/60 to-transparent">
            <h1 class="text-3xl md:text-4xl font-bold text-white drop-shadow-md">
                {{ $globalSiteName ?? 'Nuestro Menú' }}
            </h1>
            <p class="text-white/90 text-sm mt-1 font-medium">¡Bienvenido! Pide lo mejor.</p>
        </div>
    </div>
    <!-- Encabezado -->
    <flux:header>
        <flux:heading size="xl" weight="semibold">
            {{ __('Menu Fastfood') }}
        </flux:heading>
    </flux:header>

    {{-- Tabs de Categorías --}}
    <div
        class="flex flex-wrap items-center justify-start gap-2 border-b border-zinc-200 dark:border-zinc-700 pb-4 mb-6">

        {{-- TAB "Todos" --}}
        <button wire:click="seleccionarCategoria(null)"
            class="px-4 py-2 rounded-lg text-sm font-medium transition cursor-pointer active:scale-95
                {{ $categoriaSeleccionada === null
                    ? 'bg-emerald-600 text-white shadow-md'
                    : 'bg-transparent text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700' }}">
            Todos
        </button>

        {{-- Tabs dinámicos --}}
        @foreach ($categorias as $categoria)
            <button wire:click="seleccionarCategoria({{ $categoria->id }})"
                class="px-4 py-2 rounded-lg text-sm font-medium transition cursor-pointer active:scale-95
                    {{ $categoriaSeleccionada === $categoria->id
                        ? 'bg-emerald-600 text-white shadow-md'
                        : 'bg-transparent text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700' }}">
                {{ $categoria->nombre }}
            </button>
        @endforeach

    </div>
    {{-- Barra de busqueda --}}
    <div class="max-w-md mx-auto mb-8">
        <x-input wire:model.live="search" type="text" placeholder="Buscar..." />
    </div>
    {{-- Lista de productos --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($productos as $producto)
            <x-card class="flex flex-col h-full p-0 overflow-hidden">

                <!-- Imagen -->
                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}"
                    class="w-full h-44 object-cover">

                <div class="p-4 flex flex-col h-full">
                    <h2 class="text-xl dark:text-white font-semibold mb-1">{{ $producto->nombre }}</h2>
                    <p class="text-neutral-600 dark:text-neutral-400 text-sm mb-3">{{ $producto->descripcion }}</p>

                    <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">
                        ${{ number_format($producto->precio, 0, ',', '.') }}
                    </p>

                    <x-button variant="primary" size="md" class="mt-auto w-full cursor-pointer"
                        wire:click="addToCart({{ $producto->id }})">
                        Agregar al pedido
                    </x-button>
                </div>

            </x-card>
        @empty
            <p class="text-center text-neutral-500 w-full col-span-full">No hay productos disponibles.</p>
        @endforelse
    </div>
    <div class="mt-6">
        {{ $productos->links() }}
    </div>
    {{-- BOTÓN FLOTANTE PARA ABRIR SIDEBAR EN MÓVIL --}}
    @if (!empty($cart))
        <button
            class="fixed bottom-5 right-5 bg-emerald-600 text-white px-5 py-3 rounded-full shadow-xl z-50 cursor-pointer hover:scale-105 transition-transform"
            wire:click="$set('sidebarOpen', true)">
            <i class="fas fa-shopping-cart"></i> Ver Pedido ({{ count($cart) }})
        </button>
    @endif

    {{-- OVERLAY MÓVIL --}}
    @if ($sidebarOpen)
        <div class="fixed inset-0 bg-black/50 z-40 cursor-pointer" wire:click="$set('sidebarOpen', false)">
        </div>
    @endif

    {{-- SIDEBAR --}}
    @if (!empty($cart) || $sidebarOpen)

        <div
            class="
        fixed top-0 right-0 w-80 h-full bg-white dark:bg-zinc-900 shadow-xl z-50 p-5 overflow-y-auto
        transform transition-transform duration-300
        {{ $sidebarOpen ? 'translate-x-0' : 'translate-x-full' }}
        
    ">

            {{-- Botón cerrar solo móvil --}}
            <button class="lg:hidden text-2xl absolute top-3 right-3 text-zinc-500 cursor-pointer hover:text-zinc-700"
                wire:click="$set('sidebarOpen', false)">
                <i class="fas fa-xmark"></i>
            </button>

            <h2 class="text-xl font-bold mb-4 dark:text-white mt-6 lg:mt-0">Tu Pedido</h2>

            {{-- ITEMS DEL CARRITO --}}
            @forelse ($cart as $item)
                <div class="flex justify-between items-center py-2 border-b border-zinc-200 dark:border-zinc-700">
                    <div>
                        <p class="font-semibold dark:text-white">{{ $item['nombre'] }}</p>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                            ${{ number_format($item['precio']) }}
                        </p>

                        <div class="flex items-center gap-2 mt-2">
                            {{-- Botón Menos: AGREGADO cursor-pointer --}}
                            <button wire:click="disminuirCantidad({{ $item['id'] }})"
                                class="px-2 py-1 bg-zinc-200 dark:bg-zinc-700 rounded cursor-pointer hover:bg-zinc-300 dark:hover:bg-zinc-600 transition"><i
                                    class="fas fa-minus"></i></button>

                            <span class="dark:text-white">{{ $item['cantidad'] }}</span>

                            {{-- Botón Mas: AGREGADO cursor-pointer --}}
                            <button wire:click="aumentarCantidad({{ $item['id'] }})"
                                class="px-2 py-1 bg-zinc-200 dark:bg-zinc-700 rounded cursor-pointer hover:bg-zinc-300 dark:hover:bg-zinc-600 transition"><i
                                    class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <button wire:click="eliminarProducto({{ $item['id'] }})"
                        class="text-red-600 hover:text-red-800 text-lg cursor-pointer transition"><i
                            class="fas fa-xmark"></i></button>
                </div>
            @empty
                <p class="text-zinc-500 dark:text-zinc-400">No has agregado productos.</p>
            @endforelse

            {{-- NUEVO: Input para el Nombre del Cliente --}}
            @if (!empty($cart))
                <div class="mt-6 border-t border-zinc-200 dark:border-zinc-700 pt-4">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        ¿A nombre de quién?
                    </label>
                    <x-input wire:model.live="nombreCliente" type="text" placeholder="Ej. Juan Pérez"
                        class="w-full" />
                    @error('nombreCliente')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            @endif

            <div class="mt-4">
                <x-button class="w-full cursor-pointer" variant="primary" wire:click="finalizarPedido">
                    Finalizar Pedido
                </x-button>
            </div>
        </div>

    @endif
</div>
