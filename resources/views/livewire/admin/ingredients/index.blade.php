<div class="w-full space-y-6">

    {{-- Encabezado --}}
    <x-header title="Inventario de Insumos" description="Gestiona los ingredientes, costos y niveles de stock.">
        <x-button wire:click="create" variant="primary" class="gap-2">
            <i class="fas fa-plus"></i> Nuevo Insumo
        </x-button>
    </x-header>

    {{-- Tabla --}}
    <x-table>
        <x-slot name="tools">
            <div class="relative max-w-sm">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-zinc-400">
                    <i class="fas fa-magnifying-glass"></i>
                </div>
                <x-input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por nombre o código..." class="pl-10" />
            </div>
        </x-slot>

        <x-slot name="header">
            <th class="px-6 py-4">Código</th>
            <th class="px-6 py-4">Ingrediente</th>
            <th class="px-6 py-4">Costo Unit.</th>
            <th class="px-6 py-4">Stock Actual</th>
            <th class="px-6 py-4">Estado</th>
            <th class="px-6 py-4 text-right">Acciones</th>
        </x-slot>

        @forelse ($ingredients as $ingredient)
            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                
                {{-- Código --}}
                <td class="px-6 py-4 font-mono text-xs text-zinc-500">
                    {{ $ingredient->code }}
                </td>

                {{-- Nombre --}}
                <td class="px-6 py-4 font-medium text-zinc-900 dark:text-zinc-100">
                    {{ $ingredient->name }}
                    <span class="text-xs text-zinc-400 block">({{ $ingredient->unit }})</span>
                </td>

                {{-- Costo --}}
                <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">
                    ${{ number_format($ingredient->cost, 2) }}
                </td>

                {{-- Stock (Con alerta visual) --}}
                <td class="px-6 py-4 font-bold {{ $ingredient->stock <= $ingredient->min_stock ? 'text-red-600 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                    {{ floatval($ingredient->stock) }} {{ $ingredient->unit }}
                </td>

                {{-- Estado Badge --}}
                <td class="px-6 py-4">
                    @if($ingredient->stock <= 0)
                        <x-badge color="red">Agotado</x-badge>
                    @elseif($ingredient->stock <= $ingredient->min_stock)
                        <x-badge color="orange">Bajo Stock</x-badge>
                    @else
                        <x-badge color="green">Normal</x-badge>
                    @endif
                </td>

                {{-- Acciones --}}
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <button wire:click="edit({{ $ingredient->id }})" class="text-zinc-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors p-1">
                            <i class="fas fa-pencil"></i>
                        </button>
                        <button wire:click="delete({{ $ingredient->id }})" wire:confirm="¿Eliminar este ingrediente?" class="text-zinc-500 hover:text-red-600 dark:hover:text-red-400 transition-colors p-1">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-zinc-500">
                    No hay ingredientes registrados.
                </td>
            </tr>
        @endforelse

        <x-slot name="footer">
            {{ $ingredients->links() }}
        </x-slot>
    </x-table>

    {{-- MODAL --}}
    <x-modal wire:model="showModal" :title="$isEditing ? 'Editar Insumo' : 'Registrar Insumo'">
        <form wire:submit.prevent="save" class="space-y-4">
            
            <div class="grid grid-cols-2 gap-4">
                {{-- Nombre --}}
                <div class="col-span-2">
                    <x-label for="name">Nombre del Insumo</x-label>
                    <x-input id="name" wire:model="name" placeholder="Ej: Carne de Res Premium" />
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Código --}}
                <div>
                    <x-label for="code">Código (SKU)</x-label>
                    <x-input id="code" wire:model="code" placeholder="ING-001" />
                    @error('code') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Unidad --}}
                <div>
                    <x-label for="unit">Unidad de Medida</x-label>
                    <x-select id="unit" wire:model="unit">
                        <option value="">Seleccionar...</option>
                        <option value="unid">Unidad (pza)</option>
                        <option value="kg">Kilogramos (kg)</option>
                        <option value="g">Gramos (g)</option>
                        <option value="l">Litros (l)</option>
                        <option value="ml">Mililitros (ml)</option>
                    </x-select>
                    @error('unit') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Costo --}}
                <div>
                    <x-label for="cost">Costo Unitario ($)</x-label>
                    <x-input id="cost" type="number" step="0.01" wire:model="cost" placeholder="0.00" />
                    @error('cost') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Stock Actual --}}
                <div>
                    <x-label for="stock">Stock Inicial</x-label>
                    <x-input id="stock" type="number" step="0.001" wire:model="stock" placeholder="0" />
                    @error('stock') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Stock Minimo --}}
                <div class="col-span-2">
                    <x-label for="min_stock">Alerta de Stock Mínimo</x-label>
                    <x-input id="min_stock" type="number" step="0.001" wire:model="min_stock" placeholder="Ej: 5 (Avisar cuando queden 5)" />
                    <p class="text-xs text-zinc-500 mt-1">El sistema mostrará una alerta cuando el stock baje de esta cantidad.</p>
                    @error('min_stock') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-2 border-t border-zinc-100 dark:border-zinc-700">
                <x-button wire:click="$set('showModal', false)" variant="secondary">Cancelar</x-button>
                <x-button type="submit" variant="primary">Guardar</x-button>
            </div>
        </form>
    </x-modal>

    <x-toast />
</div>