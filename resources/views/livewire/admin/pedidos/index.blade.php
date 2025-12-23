<div class="w-full">
    <flux:header>
        <flux:heading size="xl" weight="semibold">
            {{ __('Pedidos') }}
        </flux:heading>
    </flux:header>

    <div class="mt-6 bg-white dark:bg-zinc-900 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700 p-4">
        {{-- NUEVO: Barra de Búsqueda --}}
        <div class="mb-4">
            <div class="relative max-w-sm">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    {{-- Icono Lupa (SVG simple) --}}
                    <i class="fas fa-magnifying-glass"></i>
                </div>
                {{-- Input conectado a Livewire --}}
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    class="block w-full p-2 pl-10 text-sm text-zinc-900 border border-zinc-300 rounded-lg bg-zinc-50 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-zinc-800 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-emerald-500 dark:focus:border-emerald-500" 
                    placeholder="Buscar ticket, cliente, mesa..."
                >
            </div>
        </div>
        <table class="w-full text-sm">
            <thead class="text-left border-b border-zinc-200 dark:border-zinc-700">
                <tr>
                    <th class="py-2">Ticket</th>
                    <th class="py-2">Cliente</th>
                    <th class="py-2">Total</th>
                    <th class="py-2">Mesa</th>
                    <th class="py-2">Estado</th>
                    <th class="py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pedidos as $pedido)
                    <tr class="border-b border-zinc-100 dark:border-zinc-800">
                        <td class="py-3 font-semibold">{{ $pedido->codigo_ticket }}</td>
                        <td>{{ $pedido->nombre_cliente ?? '-' }}</td>

                        <td class="font-bold text-emerald-600">
                            ${{ number_format($pedido->total, 0, ',', '.') }}
                        </td>

                        <td>
                            {{ $pedido->mesa?->nombre ?? 'Sin asignar' }}
                        </td>

                        <td>
                            <span
                                class="
                                px-3 py-1 rounded-full text-xs
                                @if ($pedido->estado == 'pendiente') 
                                    bg-yellow-100 text-yellow-800
                                @else
                                    bg-emerald-100 text-emerald-700 
                                @endif
                            ">
                                {{ ucfirst($pedido->estado) }}
                            </span>
                        </td>

                        <td class="flex gap-2 py-3">

                            <x-button size="sm" wire:click="openVerDetalleModal({{ $pedido->id }})" class="cursor-pointer" tooltip="Ver Detalle">
                                <i class="fas fa-eye"></i>
                            </x-button>

                            @if ($pedido->estado == 'pendiente')
                                <x-button size="sm" variant="secondary"
                                    wire:click="openAsignarMesaModal({{ $pedido->id }})">
                                    Asignar Mesa
                                </x-button>
                            @endif
                            @if ($pedido->estado == 'servido')
                                <x-button size="sm" variant="danger"
                                    wire:click="marcarComoPagado({{ $pedido->id }})">
                                    Pagar
                                </x-button>
                            @endif

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-zinc-500">
                            No hay pedidos registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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
                        <select wire:model="mesa_id"
                            class="w-full border-zinc-300 rounded-lg dark:border-zinc-700 dark:bg-zinc-800">
                            <option value="">Seleccione una mesa</option>

                            @foreach ($mesas as $mesa)
                                <option value="{{ $mesa->id }}">
                                    # {{ $mesa->numero }} - Capacidad: {{ $mesa->capacidad }}
                                </option>
                            @endforeach
                        </select>

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


        {{-- paginacion --}}
        <div class="mt-6">
            {{ $pedidos->links() }}
        </div>
    </div>
</div>
