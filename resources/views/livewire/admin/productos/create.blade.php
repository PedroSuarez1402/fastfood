<div class="max-2-3xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">
            {{ __('Nuevo Producto') }}
        </h1>
        <a href="{{ route('admin.productos.index')}}" class="px-4 py-2 bg-zinc-200 hover:bg-zinc-300 dark:bg-zinc-700 dark:hover:bg-zinc-600 rounded-lg text-sm font-medium">
            volver
        </a>
    </div>
    @if (session('success'))
        <div class="bg-emerald-100 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-5">
        {{-- Categoria --}}
        <div>
            <x-label for="categoria_id">Categoria</x-label>
            <x-select wire:model="categoria_id" id="categoria_id">
                <option value="">Selecciona una categoria</option>
                @foreach ($categorias as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                @endforeach
            </x-select>
            @error('categoria_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
        {{-- Nombre --}}
        <div>
            <x-label for="nombre">Nombre</x-label>
            <x-input wire:model="nombre" id="nombre" placeholder="Nombre del producto" />
            @error('nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        {{-- Descripcion --}}
        <div>
            <x-label for="descripcion">Descripción</x-label>
            <x-textarea wire:model="descripcion" id="descripcion" rows="3" />
            @error('descripcion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        {{-- Precio --}}
        <div>
            <x-label for="precio">Precio</x-label>
            <x-input wire:model="precio" id="precio" type="number" step="0.01" />
            @error('precio') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        {{-- Imagen --}}
        <div>
            <x-label for="imagen">Imagen</x-label>
            <x-file-input wire:model="imagen" id="imagen" accept="image/*" />

            @if ($imagen)
                <img src="{{ $imagen->temporaryUrl() }}" class="mt-3 w-32 h-32 object-cover rounded-lg border">
            @endif

            @error('imagen') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        {{-- Disponible --}}
        <div class="flex items-center gap-2">
            <input type="checkbox" wire:model="disponible" id="disponible"
                   class="w-4 h-4 rounded border-zinc-300 dark:border-zinc-700 text-emerald-600 focus:ring-emerald-500">
            <x-label for="disponible" class="!mb-0">Disponible</x-label>
        </div>
        <!-- Botón de guardar -->
        <div class="pt-4">
            <x-button type="submit" class="">
                {{ __('Guardar') }}
            </x-button>
        </div>
    </form>
</div>
