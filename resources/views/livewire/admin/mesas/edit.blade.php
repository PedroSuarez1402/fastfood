<div class="space-y-6">

    {{-- Header --}}
    <flux:header>
        <flux:heading size="xl" weight="semibold">
            Mesa #{{ $mesa->numero }} (Capacidad: {{ $mesa->capacidad }})
        </flux:heading>
    </flux:header>

    {{-- Estado de mesa --}}
    <div class="p-4 bg-gray-100 dark:bg-zinc-800 rounded border border-gray-200 dark:border-zinc-700">
        <p class="text-gray-800 dark:text-gray-200">
            <strong class="font-semibold">Estado:</strong>

            @if($mesa->estado === 'ocupada')
            <span class="text-red-600 dark:text-red-400 font-bold">Ocupada</span>
            @else
            <span class="text-green-600 dark:text-green-400 font-bold">Disponible</span>
            @endif
        </p>
    </div>

    {{-- Pedido actual --}}
    @if($pedido)
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
                @foreach($pedido->detalles as $detalle)
                <tr>
                    <td class="py-2 text-gray-800 dark:text-gray-200">
                        {{ $detalle->producto->nombre }}
                    </td>

                    <td>
                        <input type="number" min="1"
                            wire:change="actualizarCantidad({{ $detalle->id }}, $event.target.value)"
                            value="{{ $detalle->cantidad }}" class="w-16 border border-gray-300 dark:border-zinc-600 
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
                            X
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
    <p class="text-gray-600 dark:text-gray-400 italic">
        La mesa está disponible. No hay pedido activo.
    </p>
    @endif


    {{-- Agregar producto --}}
    <div class="p-4 bg-white dark:bg-zinc-900 shadow rounded border border-gray-200 dark:border-zinc-700 space-y-3">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            Agregar producto
        </h3>

        <select wire:model="producto_id" class="border border-gray-300 dark:border-zinc-600 
                   bg-white dark:bg-zinc-800 
                   text-gray-900 dark:text-gray-100 
                   rounded p-2 w-full focus:ring-blue-500">
            <option value="">Seleccionar producto...</option>
            @foreach($productos as $prod)
            <option value="{{ $prod->id }}">
                {{ $prod->nombre }} - ${{ number_format($prod->precio) }}
            </option>
            @endforeach
        </select>

        <input type="number" min="1" wire:model="cantidad" class="border border-gray-300 dark:border-zinc-600 
                   bg-white dark:bg-zinc-800 
                   text-gray-900 dark:text-gray-100 
                   p-2 rounded w-24 focus:ring-blue-500">

        <button wire:click="agregarProducto" class="px-4 py-2 bg-blue-600 dark:bg-blue-500 
                   hover:bg-blue-700 dark:hover:bg-blue-600 
                   text-white font-semibold rounded">
            Añadir al pedido
        </button>
    </div>

</div>