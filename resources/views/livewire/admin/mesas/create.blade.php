<div class="w-full space-y-6">

    <flux:header>
        <flux:heading size="xl" weight="semibold">
            {{__('Crear Mesa')}}
        </flux:heading>
    </flux:header>

    <form wire:submit.prevent="save" class="space-y-6 bg-white dark:bg-zinc-900 rounded-xl p-6 shadow">

        {{-- Número de mesa --}}
        <div class="space-y-2">
            <label class="font-medium">Número de Mesa</label>
            <flux:input 
                type="number" 
                wire:model="numero" 
                placeholder="Ej: 1"
            />
            @error('numero')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        {{-- Capacidad --}}
        <div class="space-y-2">
            <label class="font-medium">Capacidad</label>
            <flux:input 
                type="number" 
                wire:model="capacidad" 
                placeholder="Ej: 4"
            />
            @error('capacidad')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        {{-- Estado --}}
        <div class="space-y-2">
            <label class="font-medium">Estado</label>
            <flux:select wire:model="estado">
                <option value="disponible">Disponible</option>
                <option value="ocupada">Ocupada</option>
            </flux:select>
        </div>

        {{-- Botones --}}
        <div class="flex justify-end gap-3">
            <x-button 
                variant="secondary"
                href="{{ route('admin.mesas.index') }}"
                wire:navigate
            >
                Cancelar
            </x-button>

            <flux:button type="submit" icon="check">
                Guardar
            </flux:button>
        </div>

    </form>

</div>
