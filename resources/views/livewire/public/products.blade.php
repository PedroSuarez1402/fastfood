<div class="w-full">
    <!-- Encabezado -->
    <flux:header>
        <flux:heading size="xl" weight="semibold">
            {{ __('Menu Fastfood') }}
        </flux:heading>
    </flux:header>

    {{-- Tabs de Categorías --}}
    <div class="flex flex-wrap items-center justify-start gap-2 border-b border-zinc-200 dark:border-zinc-700 pb-4 mb-6">

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
                        wire:click="addToCart({{ $producto->id }})"
                        >
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
        <button class="fixed bottom-5 right-5 bg-emerald-600 text-white px-5 py-3 rounded-full shadow-xl z-50 cursor-pointer hover:scale-105 transition-transform"
            wire:click="$set('sidebarOpen', true)">
            <i class="fas fa-shopping-cart"></i> Ver Pedido ({{ count($cart) }})
        </button>
    @endif

    {{-- OVERLAY MÓVIL --}}
    @if ($sidebarOpen)
        <div class="fixed inset-0 bg-black/50 z-40 cursor-pointer" 
            wire:click="$set('sidebarOpen', false)"
            >
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
                            class="px-2 py-1 bg-zinc-200 dark:bg-zinc-700 rounded cursor-pointer hover:bg-zinc-300 dark:hover:bg-zinc-600 transition"><i class="fas fa-minus"></i></button>

                        <span class="dark:text-white">{{ $item['cantidad'] }}</span>

                        {{-- Botón Mas: AGREGADO cursor-pointer --}}
                        <button wire:click="aumentarCantidad({{ $item['id'] }})"
                            class="px-2 py-1 bg-zinc-200 dark:bg-zinc-700 rounded cursor-pointer hover:bg-zinc-300 dark:hover:bg-zinc-600 transition"><i class="fas fa-plus"></i></button>
                    </div>
                </div>

                <button wire:click="eliminarProducto({{ $item['id'] }})"
                    class="text-red-600 hover:text-red-800 text-lg cursor-pointer transition"><i class="fas fa-xmark"></i></button>
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
                <x-input 
                    wire:model.live="nombreCliente" 
                    type="text" 
                    placeholder="Ej. Juan Pérez" 
                    class="w-full"
                />
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