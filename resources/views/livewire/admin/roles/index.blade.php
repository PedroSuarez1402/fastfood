<div class="w-full space-y-6">
    <x-header
    title="Roles"
    description="Administra los roles de los usuarios.">
        <x-button wire:click="create" variant="primary" class="gap-2">
            <i class="fas fa-plus"></i>
            Nuevo Rol
        </x-button>
    </x-header>

    <x-table>
        <x-slot name="tools">
            <div class="relative max-w-sm">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-zinc-400">
                    <i class="fas fa-magnifying-glass"></i>
                </div>
                <x-input 
                    wire:model.live.debounce.300ms="search" 
                    type="text"
                    placeholder="Buscar..."
                />
            </div>
        </x-slot>

        <x-slot name="header">
            <th class="px-6 py-4">Nombre del Rol</th>
            <th class="px-6 py-4 text-center">Permisos Asignados</th>
            <th class="px-6 py-4">Usuarios</th>
            <th class="px-6 py-4 text-right">Acciones</th>
        </x-slot>
        @forelse ($roles as $role)
            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                
                {{-- Nombre --}}
                <td class="px-6 py-4 font-medium text-zinc-900 dark:text-zinc-100">
                    {{ $role->name }}
                </td>

                {{-- Cantidad de Permisos --}}
                <td class="px-6 py-4 text-center">
                    @if ($role->name === 'SuperAdmin')
                        <x-badge color="purple">Acceso Total</x-badge>
                    @else
                        <x-badge color="blue">{{ $role->permissions_count }} Permisos</x-badge>
                    @endif
                </td>

                {{-- Usuarios con este rol --}}
                <td class="px-6 py-4 text-sm text-zinc-500">
                    {{ $role->users()->count() }} usuarios
                </td>

                {{-- Acciones --}}
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        @if ($role->name !== 'SuperAdmin')
                            <button wire:click="edit({{ $role->id }})"
                                class="text-zinc-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors p-1">
                                <i class="fas fa-pencil"></i>
                            </button>

                            <button wire:click="delete({{ $role->id }})"
                                wire:confirm="¿Seguro que deseas eliminar el rol {{ $role->name }}?"
                                class="text-zinc-500 hover:text-red-600 dark:hover:text-red-400 transition-colors p-1">
                                <i class="fas fa-trash"></i>
                            </button>
                        @else
                            <span class="text-xs text-zinc-400 italic">Sistema</span>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="px-6 py-12 text-center text-zinc-500">
                    No se encontraron roles.
                </td>
            </tr>
        @endforelse
        <x-slot name="footer">
            {{ $roles->links() }}
        </x-slot>
    </x-table>
    {{-- MODAL CREAR/EDITAR --}}
    <x-modal wire:model="showModal" :title="$isEditing ? 'Editar Rol' : 'Crear Nuevo Rol'" maxWidth="lg">
        <form wire:submit.prevent="save" class="space-y-6">
            
            {{-- Nombre del Rol --}}
            <div>
                <x-label for="name">Nombre del Rol</x-label>
                <x-input id="name" wire:model="name" placeholder="Ej: Gerente de Turno" />
                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Lista de Permisos (Grid de Checkboxes) --}}
            <div>
                <x-label class="mb-3">Asignar Permisos</x-label>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-zinc-50 dark:bg-zinc-800/50 max-h-64 overflow-y-auto">
                    @foreach ($permissions as $permission)
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <div class="flex items-center h-5">
                                {{-- Usamos el value para que Livewire llene el array selectedPermissions --}}
                                <x-checkbox wire:model="selectedPermissions" value="{{ $permission->name }}" />
                            </div>
                            <div class="text-sm">
                                <span class="font-medium text-zinc-700 dark:text-zinc-300 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                    {{-- Formateamos el nombre (ej: gestionar_usuarios -> Gestionar Usuarios) --}}
                                    {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                </span>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('selectedPermissions') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Footer --}}
            <div class="flex justify-end gap-3 pt-2 border-t border-zinc-100 dark:border-zinc-700">
                <x-button wire:click="$set('showModal', false)" variant="secondary">Cancelar</x-button>
                <x-button type="submit" variant="primary">Guardar Rol</x-button>
            </div>
        </form>
    </x-modal>

    <x-toast />
</div>
