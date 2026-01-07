<div class="w-full space-y-6">
    <x-header
    title="Pedidos"
    description="Administra los pedidos del restaurante.">
    </x-header>
    <x-table>
        
        {{-- Slot: Tools (Barra de Búsqueda) --}}
        <x-slot name="tools">
            <div class="relative max-w-sm">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-zinc-400">
                    <i class="fas fa-magnifying-glass"></i>
                </div>
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    class="block w-full p-2 pl-10 text-sm rounded-lg border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 focus:ring-emerald-500 focus:border-emerald-500 placeholder-zinc-400" 
                    placeholder="Buscar ticket, cliente o mesa..."
                >
            </div>
        </x-slot>

        {{-- Slot: Header (Columnas) --}}
        <x-slot name="header">
            <th class="px-6 py-4">Ticket</th>
            <th class="px-6 py-4">Cliente</th>
            <th class="px-6 py-4">Total</th>
            <th class="px-6 py-4">Mesa</th>
            <th class="px-6 py-4">Fecha</th>
            <th class="px-6 py-4">Estado</th>
            <th class="px-6 py-4 text-right">Acciones</th>
        </x-slot>

        {{-- Slot Principal: Body (Filas) --}}
        @forelse ($pedidos as $pedido)
            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                
                <td class="px-6 py-4 font-mono font-medium text-zinc-900 dark:text-zinc-100">
                    {{ $pedido->codigo_ticket }}
                </td>

                <td class="px-6 py-4">
                    {{ $pedido->nombre_cliente ?? 'Cliente General' }}
                </td>

                <td class="px-6 py-4 font-bold text-emerald-600 dark:text-emerald-400">
                    ${{ number_format($pedido->total, 0, ',', '.') }}
                </td>

                <td class="px-6 py-4">
                    @if($pedido->mesa)
                        <span class="inline-flex items-center gap-1.5 text-zinc-700 dark:text-zinc-300 font-medium">
                            <i class="fas fa-burger text-zinc-400 dark:text-zinc-500"></i>
                            Mesa # {{ $pedido->mesa->numero }}
                        </span>
                    @else
                        <span class="text-zinc-400 italic text-xs">Sin asignar</span>
                    @endif
                </td>

                <td class="px-6 py-4 text-zinc-500 text-xs">
                    {{ $pedido->fecha_pedido }}
                </td>

                <td class="px-6 py-4">
                    @php
                        $color = match($pedido->estado) {
                            'pendiente' => 'yellow',
                            'servido' => 'blue',
                            'pagado', 'completado' => 'green',
                            'anulado' => 'red',
                            default => 'zinc',
                        };
                    @endphp
                    <x-badge :color="$color">
                        {{ ucfirst($pedido->estado) }}
                    </x-badge>
                </td>

                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        {{-- Botones de Acción --}}
                        <x-button size="sm" variant="ghost" wire:click="openVerDetalleModal({{ $pedido->id }})" title="Ver Detalle">
                            <i class="fas fa-eye text-zinc-500 hover:text-blue-600 transition-colors"></i>
                        </x-button>

                        @if ($pedido->estado == 'pendiente')
                            <x-button size="sm" variant="secondary" wire:click="openAsignarMesaModal({{ $pedido->id }})">
                                Mesa
                            </x-button>
                        @endif

                        @if ($pedido->estado == 'servido')
                            <x-button size="sm" variant="primary" wire:click="marcarComoPagado({{ $pedido->id }})">
                                Cobrar
                            </x-button>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-zinc-500">
                    <div class="flex flex-col items-center justify-center gap-2">
                        <i class="fas fa-box-open text-4xl text-zinc-300"></i>
                        <span>No se encontraron pedidos.</span>
                    </div>
                </td>
            </tr>
        @endforelse

        {{-- Slot: Footer (Paginación) --}}
        <x-slot name="footer">
            {{ $pedidos->links() }}
        </x-slot>

    </x-table>
        {{-- Modal Asignar Mesa --}}
        <x-modal title="Asignar Mesa" maxWidth="lg" wire:model="showAsignarMesaModal">

            @if ($pedidoSeleccionado)
                <div class="space-y-4">
                    <p class="text-sm text-zinc-600 dark:text-zinc-300">
                        Asignando mesa al pedido:
                        <span class="font-semibold">{{ $pedidoSeleccionado->codigo_ticket }}</span>
                    </p>

                    <div>
                        <label class="block mb-1 text-sm font-medium">Seleccionar Mesa</label>
                        <x-select wire:model="mesa_id"
                            >
                            <option value="">Seleccione una mesa</option>

                            @foreach ($mesas as $mesa)
                                <option value="{{ $mesa->id }}">
                                    # {{ $mesa->numero }} - Capacidad: {{ $mesa->capacidad }}
                                </option>
                            @endforeach
                        </x-select>

                        @error('mesa_id')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <x-button variant="ghost" wire:click="$set('showAsignarMesaModal', false)">
                            Cancelar
                        </x-button>

                        <x-button variant="primary" wire:click="asignarMesa">
                            Guardar
                        </x-button>
                    </div>
                </div>
            @endif

        </x-modal>
        {{-- Modal Ver Detalle --}}
        <x-modal title="Detalle del Pedido" maxWidth="lg" wire:model="showVerDetalleModal">

            @if ($pedidoSeleccionado)
                <div class="space-y-4">

                    {{-- Encabezado --}}
                    <div class="p-3 bg-zinc-100 dark:bg-zinc-800 rounded-lg">
                        <p class="text-sm text-zinc-600 dark:text-zinc-300">
                            Ticket:
                            <span class="font-semibold">{{ $pedidoSeleccionado->codigo_ticket }}</span>
                        </p>

                        <p class="text-sm text-zinc-600 dark:text-zinc-300">
                            Cliente:
                            <span class="font-semibold">{{ $pedidoSeleccionado->nombre_cliente ?? '—' }}</span>
                        </p>

                        <p class="text-sm text-zinc-600 dark:text-zinc-300">
                            Mesa:
                            <span class="font-semibold">
                                {{ $pedidoSeleccionado->mesa?->nombre ?? 'Sin asignar' }}
                            </span>
                        </p>

                        <p class="text-sm text-zinc-600 dark:text-zinc-300">
                            Estado:
                            <span class="font-semibold capitalize">
                                {{ $pedidoSeleccionado->estado }}
                            </span>
                        </p>
                    </div>

                    {{-- Detalle de productos --}}
                    <div class="max-h-80 overflow-y-auto border rounded-lg">
                        <table class="w-full text-sm">
                            <thead class="bg-zinc-100 dark:bg-zinc-800">
                                <tr>
                                    <th class="py-2 px-3 text-left">Producto</th>
                                    <th class="py-2 px-3 text-center">Cantidad</th>
                                    <th class="py-2 px-3 text-right">Subtotal</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($pedidoSeleccionado->detalles as $detalle)
                                    <tr class="border-b border-zinc-200 dark:border-zinc-700">
                                        <td class="py-2 px-3">
                                            {{ $detalle->producto->nombre }}
                                        </td>

                                        <td class="py-2 px-3 text-center">
                                            {{ $detalle->cantidad }}
                                        </td>

                                        <td class="py-2 px-3 text-right font-semibold">
                                            ${{ number_format($detalle->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Total --}}
                    <div class="flex justify-end">
                        <div class="text-right">
                            <p class="text-lg font-bold text-emerald-600">
                                Total:
                                ${{ number_format($pedidoSeleccionado->total, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    {{-- Botón cerrar --}}
                    <div class="flex justify-end pt-4">
                        <x-button variant="primary" wire:click="$set('showVerDetalleModal', false)">
                            Cerrar
                        </x-button>
                    </div>
                </div>
            @endif
        </x-modal>
    </div>
</div>
