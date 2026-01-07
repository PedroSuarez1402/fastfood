<div class="mx-auto max-2-3xl space-y-6">
    
    <div class="flex items-center justify-between">
        <x-header 
            title="Editar Producto"
            description="Edita el producto {{ $producto->nombre }}">
        </x-header>
        <a href="{{ route('admin.productos.index') }}"
            class="px-4 py-2 text-sm font-medium rounded-lg bg-zinc-200 hover:bg-zinc-300 dark:bg-zinc-700 dark:hover:bg-zinc-600">
            volver
        </a>
    </div>

    @if (session('success'))
        <div class="p-3 mb-4 rounded-lg bg-emerald-100 text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="update" class="space-y-4">
        {{-- Categoría --}}
        <div>
            <x-label for="categoria_id">Categoría</x-label>
            <x-select wire:model="categoria_id" id="categoria_id">
                <option value="">Seleccione una categoría</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                @endforeach
            </x-select>
            @error('categoria_id') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        {{-- Nombre --}}
        <div>
            <x-label for="nombre">Nombre</x-label>
            <x-input wire:model="nombre" />
            @error('nombre') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        {{-- Descripción --}}
        <div>
            <x-label for="descripcion">Descripción</x-label>
            <x-textarea wire:model="descripcion" />
        </div>

        {{-- Precio --}}
        <div>
            <x-label for="precio">Precio</x-label>
            <x-input type="number" wire:model="precio" />
            @error('precio') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        {{-- SECCIÓN DE IMAGEN CON SPINNER --}}
        <div class="space-y-3">
            <x-label>Imagen del Producto</x-label>
            
            <div class="flex flex-col gap-4 md:flex-row">
                
                {{-- 1. Previsualización con Overlay de Carga --}}
                <div class="shrink-0 relative group"> {{-- 'relative' es clave aquí --}}
                    
                    {{-- SPINNER DE CARGA: Se muestra solo cuando 'nueva_imagen' se está procesando --}}
                    <div wire:loading.flex wire:target="nueva_imagen" 
                         class="absolute inset-0 z-10 flex items-center justify-center rounded-lg bg-white/70 dark:bg-black/70 backdrop-blur-[2px]">
                        <svg class="w-8 h-8 text-emerald-600 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    {{-- Imagen --}}
                    @if ($nueva_imagen)
                        <p class="mb-1 text-xs text-zinc-500">Nueva:</p>
                        <img src="{{ $nueva_imagen->temporaryUrl() }}" class="object-cover w-32 h-32 border rounded-lg">
                    @elseif($imagen)
                        <p class="mb-1 text-xs text-zinc-500">Actual:</p>
                        <img src="{{ asset('storage/' . $imagen) }}" class="object-cover w-32 h-32 border rounded-lg">
                    @endif
                </div>

                {{-- 2. Dropzone --}}
                <div class="flex-1">
                    <div wire:ignore>
                        <div class="flex items-center justify-center transition border-2 border-dashed rounded-lg dropzone border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700"
                            id="my-dropzone-edit">
                            <div class="dz-message" data-dz-message>
                                <span class="text-zinc-500 dark:text-zinc-400">
                                    Haz click o arrastra para reemplazar la imagen
                                </span>
                            </div>
                        </div>
                    </div>
                    @error('nueva_imagen') <span class="mt-2 text-sm text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- Disponible --}}
        <div class="flex items-center space-x-2">
            <input type="checkbox" wire:model.live="disponible" id="disponible"
                class="rounded border-zinc-400 text-emerald-600">
            <x-label for="disponible" class="!mb-0">Disponible</x-label>
        </div>

        {{-- BOTÓN INTELIGENTE --}}
        <div class="pt-4">
            <button type="submit"
                {{-- Deshabilita el botón mientras se carga la imagen o se envía el formulario --}}
                wire:loading.attr="disabled" 
                class="px-4 py-2 text-white transition rounded-lg bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed">
                
                {{-- Texto normal (se oculta al cargar imagen) --}}
                <span wire:loading.remove wire:target="nueva_imagen">Actualizar producto</span>
                
                {{-- Texto de carga (se muestra al subir imagen) --}}
                <span wire:loading wire:target="nueva_imagen" class="flex items-center gap-2">
                    <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Subiendo imagen...
                </span>
            </button>
        </div>
    </form>

    {{-- ASSETS Y SCRIPTS (Se mantienen igual) --}}
    @assets
        <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    @endassets

    @script
    <script>
        Dropzone.autoDiscover = false;
        let dropzoneElement = document.getElementById("my-dropzone-edit");
        if (dropzoneElement.dropzone) { dropzoneElement.dropzone.destroy(); }

        let myDropzone = new Dropzone("#my-dropzone-edit", {
            url: "#", 
            autoProcessQueue: false,
            maxFiles: 1,
            acceptedFiles: 'image/*',
            addRemoveLinks: true,
            dictRemoveFile: "Quitar imagen",
            init: function() {
                this.on("addedfile", function(file) {
                    let cleanFile = new File([file], file.name, { type: file.type });
                    setTimeout(() => {
                        $wire.upload('nueva_imagen', cleanFile, 
                            () => { console.log('Imagen subida'); }, 
                            () => { this.removeFile(file); }
                        );
                    }, 50);
                });
                this.on("removedfile", function(file) {
                    setTimeout(() => { $wire.set('nueva_imagen', null); }, 50);
                });
            }
        });
    </script>
    @endscript

    <style>
        .dropzone { padding: 1rem; min-height: 140px; }
        .dropzone .dz-preview { margin: 0; width: 120px; height: 120px; }
        .dropzone .dz-preview .dz-image { width: 100%; height: 100%; border-radius: 0.5rem; }
        .dropzone .dz-preview .dz-image img { width: 100%; height: 100%; object-fit: cover; }
        .dropzone .dz-preview { background: transparent !important; box-shadow: none !important; border: none !important; }
        .dropzone .dz-details, .dropzone .dz-success-mark, .dropzone .dz-error-mark, .dropzone .dz-progress { display: none !important; }
        .dropzone .dz-remove { margin-top: 0.5rem; font-size: 0.75rem; color: #ef4444; cursor: pointer; }
        .dropzone .dz-remove:hover { text-decoration: underline; }
        .dark .dropzone { background-color: transparent !important; }
    </style>
</div>