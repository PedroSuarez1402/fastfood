<div class="space-y-6">

    {{-- Header / Formulario de Edición de Mesa --}}
    <div class="p-4 bg-white dark:bg-zinc-900 shadow rounded border border-gray-200 dark:border-zinc-700">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                Configuración de Mesa
            </h2>
            @if (session()->has('success_mesa'))
                <span class="text-sm text-green-600 font-bold bg-green-100 px-2 py-1 rounded">
                    {{ session('success_mesa') }}
                </span>
            @endif
        </div>

        <form wire:submit.prevent="actualizarMesa" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            
            {{-- Nombre / Número --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre / Número</label>
                <input type="text" wire:model="numero" 
                    class="w-full border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-gray-100 rounded p-2 focus:ring-blue-500">
                @error('numero') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            {{-- Capacidad --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Capacidad (Personas)</label>
                <input type="number" wire:model="capacidad" min="1"
                    class="w-full border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-gray-100 rounded p-2 focus:ring-blue-500">
                @error('capacidad') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            {{-- Estado --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                <select wire:model="estado" 
                    class="w-full border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-gray-100 rounded p-2 focus:ring-blue-500">
                    <option value="disponible">Disponible</option>
                    <option value="ocupada">Ocupada</option>
                    <option value="reservada">Reservada</option>
                    <option value="mantenimiento">Mantenimiento</option>
                </select>
                @error('estado') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            {{-- Botón Guardar --}}
            <div>
                <button type="submit" 
                    class="w-full px-4 py-2 bg-gray-800 dark:bg-gray-700 hover:bg-gray-700 dark:hover:bg-gray-600 text-white font-semibold rounded transition">
                    Actualizar Mesa
                </button>
            </div>
        </form>
    </div>

    {{-- Pedido actual --}}
    @if ($pedido)
        <div class="p-4 bg-white dark:bg-zinc-900 shadow rounded border border-gray-200 dark:border-zinc-700">
            <h2 class="text-lg font-semibold mb-3 text-gray-900 dark:text-gray-100">
                Pedido {{ $pedido->codigo_ticket }}
            </h2>

            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-300 dark:border-zinc-700 bg-gray-200 dark:bg-zinc-800">
                        <th class="py-2 text-gray-900 dark:text-gray-100">Producto</th>
                        <th class="text-gray-900 dark:text-gray-100">Cant.</th>
                        <th class="text-gray-900 dark:text-gray-100">Precio</th>
                        <th class="text-gray-900 dark:text-gray-100">Subtotal</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                    @foreach ($pedido->detalles as $detalle)
                        <tr>
                            <td class="py-2 text-gray-800 dark:text-gray-200">
                                {{ $detalle->producto->nombre }}
                            </td>

                            <td>
                                <input type="number" min="1"
                                    wire:change="actualizarCantidad({{ $detalle->id }}, $event.target.value)"
                                    value="{{ $detalle->cantidad }}"
                                    class="w-16 border border-gray-300 dark:border-zinc-600 
                                           bg-white dark:bg-zinc-800 
                                           text-gray-900 dark:text-gray-100 
                                           rounded px-2 py-1 focus:ring-blue-500">
                            </td>

                            <td class="text-gray-800 dark:text-gray-300">
                                ${{ number_format($detalle->producto->precio) }}
                            </td>

                            <td class="text-gray-800 dark:text-gray-300">
                                ${{ number_format($detalle->subtotal) }}
                            </td>

                            <td>
                                <button wire:click="eliminarDetalle({{ $detalle->id }})"
                                    class="text-red-600 dark:text-red-400 font-bold hover:underline">
                                    <i class="fas fa-x"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-right mt-4 text-lg font-bold text-gray-900 dark:text-gray-100">
                Total: ${{ number_format($pedido->total) }}
            </div>
        </div>
    @else
        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 rounded border border-blue-200 dark:border-blue-800">
            <p class="italic font-medium">
                La mesa no tiene pedidos activos actualmente.
            </p>
        </div>
    @endif


    {{-- Agregar producto --}}
    <div class="p-4 bg-white dark:bg-zinc-900 shadow rounded border border-gray-200 dark:border-zinc-700 space-y-3">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            Agregar producto al pedido
        </h3>

        <div class="flex gap-2">
            <select wire:model="producto_id"
                class="border border-gray-300 dark:border-zinc-600 
                       bg-white dark:bg-zinc-800 
                       text-gray-900 dark:text-gray-100 
                       rounded p-2 w-full focus:ring-blue-500">
                <option value="">Seleccionar producto...</option>
                @foreach ($productos as $prod)
                    <option value="{{ $prod->id }}">
                        {{ $prod->nombre }} - ${{ number_format($prod->precio) }}
                    </option>
                @endforeach
            </select>
    
            <input type="number" min="1" wire:model="cantidad"
                class="border border-gray-300 dark:border-zinc-600 
                       bg-white dark:bg-zinc-800 
                       text-gray-900 dark:text-gray-100 
                       p-2 rounded w-24 focus:ring-blue-500">
    
            <button wire:click="agregarProducto"
                class="px-4 py-2 bg-blue-600 dark:bg-blue-500 
                       hover:bg-blue-700 dark:hover:bg-blue-600 
                       text-white font-semibold rounded whitespace-nowrap">
                Añadir
            </button>
        </div>
    </div>

</div>