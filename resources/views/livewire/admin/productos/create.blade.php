<div class="max-2-3xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <x-header 
            title="Nuevo Producto"
            description="Crea un nuevo producto para el restaurante.">
        </x-header>
        <a href="{{ route('admin.productos.index') }}"
            class="px-4 py-2 bg-zinc-200 hover:bg-zinc-300 dark:bg-zinc-700 dark:hover:bg-zinc-600 rounded-lg text-sm font-medium">
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
            @error('categoria_id')
                <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>
        {{-- Nombre --}}
        <div>
            <x-label for="nombre">Nombre</x-label>
            <x-input wire:model="nombre" id="nombre" placeholder="Nombre del producto" />
            @error('nombre')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        {{-- Descripcion --}}
        <div>
            <x-label for="descripcion">Descripción</x-label>
            <x-textarea wire:model="descripcion" id="descripcion" rows="3" />
            @error('descripcion')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        {{-- Precio --}}
        <div>
            <x-label for="precio">Precio</x-label>
            <x-input wire:model="precio" id="precio" type="number" step="0.01" />
            @error('precio')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        {{-- DROPZONE PARA IMAGEN --}}
        <div>
            <x-label for="imagen" class="mb-2">Imagen del Producto</x-label>

            {{-- Contenedor Dropzone --}}
            <div wire:ignore>
                <div class="dropzone border-2 border-dashed border-zinc-300 dark:border-zinc-600 rounded-lg bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition flex justify-center items-center"
                    id="my-dropzone">
                    {{-- Mensaje por defecto --}}
                    <div class="dz-message" data-dz-message>
                        <span class="text-zinc-500 dark:text-zinc-400">Arrastra tu imagen aquí o haz click</span>
                    </div>
                </div>
            </div>

            {{-- Mensajes de error de Livewire --}}
            @error('imagen')
                <span class="mt-2 text-sm text-red-500 block">{{ $message }}</span>
            @enderror
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
    {{-- ESTILOS Y SCRIPTS DE DROPZONE --}}
    @assets
        <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    @endassets
    {{-- SCRIPT DE INTEGRACIÓN --}}
    @script
        <script>
            // 1. Configuración global
            Dropzone.autoDiscover = false;

            // 2. Inicialización directa (sin addEventListener extra)
            // Usamos una variable local para evitar conflictos globales
            let dropzoneElement = document.getElementById("my-dropzone");

            // Verificamos si ya existe una instancia para no duplicarla
            if (dropzoneElement.dropzone) {
                dropzoneElement.dropzone.destroy();
            }

            let myDropzone = new Dropzone("#my-dropzone", {
                url: "#", // Dummy URL
                autoProcessQueue: false,
                maxFiles: 1,
                acceptedFiles: 'image/*',
                addRemoveLinks: true,
                dictRemoveFile: "Quitar imagen",

                init: function() {
                    this.on("addedfile", function(file) {
                        // 3. LIMPIEZA DEL ARCHIVO (Evita el error toJSON)
                        // Creamos un nuevo objeto File limpio usando los datos del original
                        let cleanFile = new File([file], file.name, {
                            type: file.type
                        });

                        // 4. Subida usando $wire (nativo de Livewire 3)
                        $wire.upload('imagen', cleanFile, (uploadedFilename) => {
                            console.log('Imagen subida correctamente');
                        }, () => {
                            console.error('Error al subir imagen');
                            this.removeFile(file); // Quitar si falla
                        });
                    });

                    this.on("removedfile", function(file) {
                        // 5. Limpiar propiedad en Livewire
                        $wire.set('imagen', null);
                    });
                }
            });
        </script>
    @endscript

    {{-- PEQUEÑO AJUSTE CSS PARA MODO OSCURO --}}
    <style>
        /* CONTENEDOR GENERAL */
        .dropzone {
            padding: 1rem;
            min-height: 160px;
        }

        /* PREVIEW */
        .dropzone .dz-preview {
            margin: 0;
            width: 140px;
            height: 140px;
        }

        /* IMAGEN */
        .dropzone .dz-preview .dz-image {
            width: 100%;
            height: 100%;
            border-radius: 0.75rem;
            overflow: hidden;
            background: transparent;
        }

        .dropzone .dz-preview .dz-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* QUITAR FONDO BLANCO */
        .dropzone .dz-preview {
            background: transparent !important;
            box-shadow: none !important;
            border: none !important;
        }

        /* OCULTAR DETALLES FEOS */
        .dropzone .dz-details,
        .dropzone .dz-success-mark,
        .dropzone .dz-error-mark,
        .dropzone .dz-progress {
            display: none !important;
        }

        /* BOTÓN QUITAR */
        .dropzone .dz-remove {
            margin-top: 0.5rem;
            font-size: 0.75rem;
            color: #ef4444;
            text-align: center;
            cursor: pointer;
        }

        .dropzone .dz-remove:hover {
            text-decoration: underline;
        }

        /* MODO OSCURO */
        .dark .dropzone {
            background-color: transparent !important;
        }
    </style>

</div>
