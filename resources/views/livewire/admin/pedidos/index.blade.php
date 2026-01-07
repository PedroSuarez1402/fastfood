<div class="w-full">
    <x-header
    title="Pedidos"
    description="Administra los pedidos del restaurante.">
    </x-header>
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        {{-- NUEVO: Barra de Búsqueda --}}
        <div class="p-4 border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-800/20">
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
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-zinc-600 dark:text-zinc-300">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-900 dark:text-zinc-100 uppercase font-semibold text-xs tracking-wider border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <th class="px-6 py-4">Ticket</th>
                        <th class="px-6 py-4">Cliente</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Mesa</th>
                        <th class="px-6 py-4">Estado</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse ($pedidos as $pedido)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            
                            {{-- Ticket --}}
                            <td class="px-6 py-4 font-mono font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $pedido->codigo_ticket }}
                            </td>

                            {{-- Cliente --}}
                            <td class="px-6 py-4">
                                {{ $pedido->nombre_cliente ?? 'Cliente General' }}
                            </td>

                            {{-- Total --}}
                            <td class="px-6 py-4 font-bold text-emerald-600 dark:text-emerald-400">
                                ${{ number_format($pedido->total, 0, ',', '.') }}
                            </td>

                            {{-- Mesa --}}
                            <td class="px-6 py-4">
                                @if($pedido->mesa)
                                    <span class="inline-flex items-center gap-1.5 text-zinc-700 dark:text-zinc-300 font-medium">
                                        {{-- Icono mesa --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 text-zinc-400">
                                            <path fill-rule="evenodd" d="M3 6a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V6Zm4.5 7.5a.75.75 0 0 1 .75.75v2.25h-3v-2.25a.75.75 0 0 1 .75-.75h1.5Zm3.75-1.5a.75.75 0 0 0-1.5 0v4.5h1.5v-4.5ZM15 9.75a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 .75.75v2.25h-3v-2.25Z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $pedido->mesa->nombre }}
                                    </span>
                                @else
                                    <span class="text-zinc-400 italic text-xs">Sin asignar</span>
                                @endif
                            </td>

                            {{-- Estado (Usando x-badge) --}}
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

                            {{-- Acciones --}}
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    
                                    {{-- Ver Detalle --}}
                                    <x-button size="sm" variant="ghost" wire:click="openVerDetalleModal({{ $pedido->id }})" title="Ver Detalle">
                                        <i class="fas fa-eye text-zinc-500 hover:text-blue-600 transition-colors"></i>
                                    </x-button>

                                    {{-- Asignar Mesa (Si pendiente) --}}
                                    @if ($pedido->estado == 'pendiente')
                                        <x-button size="sm" variant="secondary" wire:click="openAsignarMesaModal({{ $pedido->id }})">
                                            Mesa
                                        </x-button>
                                    @endif

                                    {{-- Pagar (Si servido) --}}
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
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10 text-zinc-300">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>
                                    <span>No se encontraron pedidos.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Paginación --}}
        <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50">
            {{-- Asegúrate de que en tu componente Pedidos uses use WithPagination --}}
            {{ $pedidos->links() }} 
        </div>
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


        
    </div>
</div>
