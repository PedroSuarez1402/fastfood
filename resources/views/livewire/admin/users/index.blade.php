<div class="w-full space-y-6">
    
        <x-header
            title="Gestion de Personal"
            description="Administra los usuarios, meseros y cocineros del restaurante."
        >
        <x-button wire:click="create" variant="primary" class="gap-2">
                <i class="fas fa-plus"></i>
                Nuevo Usuario
        </x-button>
    </x-header>

    {{-- Tabla Reutilizable --}}
    <x-table>
        
        {{-- Slot: Encabezados de Columna --}}
        <x-slot name="header">
            <th class="px-6 py-4">Usuario</th>
            <th class="px-6 py-4">Rol</th>
            <th class="px-6 py-4">Fecha Registro</th>
            <th class="px-6 py-4 text-right">Acciones</th>
        </x-slot>

        {{-- Slot: Cuerpo de la Tabla (Filas) --}}
        @forelse ($users as $user)
            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                
                {{-- Columna 1: Usuario --}}
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center text-zinc-600 dark:text-zinc-300 font-bold shrink-0 uppercase">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $user->name }}
                            </div>
                            <div class="text-xs text-zinc-500">
                                {{ $user->email }}
                            </div>
                        </div>
                    </div>
                </td>

                {{-- Columna 2: Roles (Con Badge) --}}
                <td class="px-6 py-4">
                    <div class="flex flex-wrap gap-1">
                        @foreach($user->roles as $role)
                            @php
                                $color = match($role->name) {
                                    'SuperAdmin' => 'red',
                                    'AdminRestaurante' => 'purple',
                                    'Cocina' => 'orange',
                                    'Mesero' => 'blue',
                                    default => 'zinc'
                                };
                            @endphp
                            <x-badge :color="$color">
                                {{ $role->name }}
                            </x-badge>
                        @endforeach
                    </div>
                </td>

                {{-- Columna 3: Fecha --}}
                <td class="px-6 py-4 text-zinc-500 text-xs">
                    {{ $user->created_at->format('d M, Y') }}
                </td>

                {{-- Columna 4: Acciones --}}
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        {{-- Editar --}}
                        <button wire:click="edit({{ $user->id }})" 
                            class="text-zinc-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                        </button>

                        {{-- Eliminar --}}
                        @if(auth()->id() !== $user->id)
                            <button wire:click="delete({{ $user->id }})" 
                                wire:confirm="¿Estás seguro de eliminar este usuario?"
                                class="text-zinc-500 hover:text-red-600 dark:hover:text-red-400 transition-colors p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="px-6 py-12 text-center text-zinc-500">
                    <div class="flex flex-col items-center justify-center gap-2">
                        {{-- Icono de "Usuarios" vacío --}}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-zinc-300">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        <span>No se encontraron usuarios registrados.</span>
                    </div>
                </td>
            </tr>
        @endforelse

        {{-- Slot: Footer (Paginación) --}}
        <x-slot name="footer">
            {{ $users->links() }}
        </x-slot>

    </x-table>

    {{-- MODAL (Usando tu componente x-modal) --}}
    <x-modal wire:model="showModal" maxWidth="lg" :title="$isEditing ? 'Editar Usuario' : 'Crear Nuevo Usuario'">
        <form wire:submit.prevent="save" class="space-y-4">
            
            {{-- Nombre --}}
            <div>
                <x-label for="name">Nombre Completo</x-label>
                <x-input id="name" wire:model="name" />
                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Email --}}
            <div>
                <x-label for="email">Correo Electrónico</x-label>
                <x-input id="email" type="email" wire:model="email" />
                @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Rol --}}
            <div>
                <x-label for="role">Rol de Acceso</x-label>
                <x-select id="role" wire:model="role">
                    <option value="">Seleccionar rol...</option>
                    @foreach($roles as $r)
                        <option value="{{ $r->name }}">{{ $r->name }}</option>
                    @endforeach
                </x-select>
                @error('role') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Password --}}
            <div>
                <x-label for="password">Contraseña</x-label>
                <x-input id="password" type="password" wire:model="password" placeholder="{{ $isEditing ? 'Dejar vacío para no cambiar' : 'Mínimo 8 caracteres' }}" />
                @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Footer del Modal --}}
            <div class="flex justify-end gap-3 mt-6 pt-2 border-t border-zinc-100 dark:border-zinc-700">
                <x-button wire:click="$set('showModal', false)" variant="secondary">
                    Cancelar
                </x-button>
                <x-button type="submit" variant="primary">
                    {{ $isEditing ? 'Guardar Cambios' : 'Crear Usuario' }}
                </x-button>
            </div>

        </form>
    </x-modal>

    {{-- Notificación Toast (Usando tu componente x-toast) --}}
    <x-toast />
</div>
