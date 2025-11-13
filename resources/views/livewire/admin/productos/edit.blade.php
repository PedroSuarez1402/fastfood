<div class="max-2-3xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">
            {{ __('Editar Producto') }}
        </h1>
        <a href="{{ route('admin.productos.index') }}"
            class="px-4 py-2 bg-zinc-200 hover:bg-zinc-300 dark:bg-zinc-700 dark:hover:bg-zinc-600 rounded-lg text-sm font-medium">
            volver
        </a>
    </div>

    @if (session('success'))
        <div class="bg-emerald-100 text-emerald-800 p-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="update" class="space-y-4">
        <div>
            <x-label for="categoria_id">Categoría</x-label>
            <x-select wire:model="categoria_id" id="categoria_id">
                <option value="">Seleccione una categoría</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                @endforeach
            </x-select>
            @error('categoria_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <x-label for="nombre">Nombre</x-label>
                <x-input wire:model="nombre" />
                @error('nombre')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
        </div>

        <div>
            <x-label for="descripcion">Descripción</x-label>
            <x-textarea wire:model="descripcion" />
        </div>

        <div>
            <x-label for="precio">Precio</x-label>
            <x-input type="number" wire:model="precio" />
            @error('precio')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center gap-4 py-2">

            {{-- Si el usuario subió una nueva imagen --}}
            @if ($nueva_imagen)
                <img src="{{ $nueva_imagen->temporaryUrl() }}" class="w-24 h-24 object-cover rounded-lg border">
            @else
                {{-- Imagen actual --}}
                <img src="{{ asset('storage/' . $imagen) }}" class="w-24 h-24 object-cover rounded-lg border">
            @endif

            <div>
                <x-label>Nueva imagen (opcional)</x-label>
                <x-file-input type="file" wire:model="nueva_imagen" />
                @error('nueva_imagen')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="flex items-center space-x-2">
            <input type="checkbox" wire:model.live="disponible" id="disponible"
                class="rounded border-zinc-400 text-emerald-600">
            <x-label for="disponible" class="!mb-0">Disponible</x-label>
        </div>

        <div class="pt-4">
            <button type="submit"
                class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition">
                Actualizar producto
            </button>
        </div>
    </form>
</div>
