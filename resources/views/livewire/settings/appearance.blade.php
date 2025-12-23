<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Appearance')" :subheading="__('Update the appearance settings for your account')">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
        </flux:radio.group>
    </x-settings.layout>

    <flux:separator variant="subtle" />

    <x-settings.layout :heading="__('Brand Identity')" :subheading="__('Update your restaurant name and logo')">

        <form wire:submit.prevent="save" class="space-y-6">
            {{-- Nombre del Restaurante --}}
            <flux:field>
                <flux:label>{{ __('Restaurant Name') }}</flux:label>
                <flux:input wire:model="site_name" type="text" />
                <flux:error name="site_name" />
            </flux:field>

            {{-- Sección Logo con Dropzone --}}
            <flux:field>
                <flux:label class="mb-3">{{ __('Restaurant Logo') }}</flux:label>

                <div class="flex flex-col gap-6 md:flex-row">

                    {{-- A. Previsualización (Izquierda) --}}
                    <div class="shrink-0 relative group">
                        {{-- Overlay de carga (Usamos tu componente x-loading-overlay) --}}
                        <x-loading-overlay target="site_logo" />

                        @if ($site_logo)
                            {{-- Caso 1: Nuevo logo subido temporalmente --}}
                            <p class="mb-2 text-xs text-zinc-500 font-medium">New Preview:</p>
                            <div
                                class="h-32 w-32 rounded-lg border dark:border-zinc-700 bg-white flex items-center justify-center p-2">
                                <img src="{{ $site_logo->temporaryUrl() }}"
                                    class="max-h-full max-w-full object-contain">
                            </div>
                        @elseif ($current_logo)
                            {{-- Caso 2: Logo guardado en BD --}}
                            <p class="mb-2 text-xs text-zinc-500 font-medium">Current Logo:</p>
                            <div
                                class="h-32 w-32 rounded-lg border dark:border-zinc-700 bg-white flex items-center justify-center p-2">
                                <img src="{{ asset('storage/' . $current_logo) }}"
                                    class="max-h-full max-w-full object-contain">
                            </div>
                        @else
                            {{-- Caso 3: Sin logo (SVG manual en lugar de flux:icon) --}}
                            <p class="mb-2 text-xs font-medium text-zinc-500">No Logo:</p>
                            <div class="flex items-center justify-center border rounded-lg h-32 w-32 bg-zinc-100 dark:bg-zinc-800 dark:border-zinc-700">
                                {{-- SVG Storefront (Tienda) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="text-zinc-400 size-10">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72m-13.5 8.65h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    {{-- B. Zona Dropzone (Derecha) --}}
                    <div class="flex-1">
                        <div wire:ignore>
                            <div class="flex items-center justify-center transition border-2 border-dashed rounded-lg dropzone border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 min-h-[128px]"
                                id="settings-dropzone">
                                <div class="dz-message" data-dz-message>
                                    <div class="flex flex-col items-center gap-2">
                                        {{-- SVG Cloud Upload (Nube) --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-zinc-400">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" />
                                        </svg>
                                        
                                        <span class="text-sm text-zinc-500 dark:text-zinc-400">
                                            Click or drag new logo here
                                        </span>
                                        <span class="text-xs text-zinc-400">PNG, JPG up to 1MB</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <flux:error name="site_logo" />
                    </div>
                </div>
            </flux:field>

            {{-- Botón de Guardar --}}
            <div class="flex justify-end pt-4">
                {{-- Usamos tu componente de botón inteligente --}}
                <x-button type="submit" variant="primary" wire:loading.attr="disabled"
                    class="disabled:opacity-50 disabled:cursor-not-allowed">

                    <span wire:loading.remove wire:target="site_logo">{{ __('Save Changes') }}</span>

                    <span wire:loading wire:target="site_logo" class="flex items-center gap-2">
                        <x-spinner size="w-4 h-4" />
                        {{ __('Uploading...') }}
                    </span>
                </x-button>
            </div>

            <flux:toast />
        </form>
    </x-settings.layout>
    {{-- ASSETS Y SCRIPTS --}}
    @assets
        <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    @endassets

    @script
    <script>
        Dropzone.autoDiscover = false;

        // ID único para este dropzone de settings
        let dropzoneId = "settings-dropzone";
        let dropzoneElement = document.getElementById(dropzoneId);
        
        // Limpieza de instancia previa
        if (dropzoneElement.dropzone) {
            dropzoneElement.dropzone.destroy();
        }

        let settingsDropzone = new Dropzone("#" + dropzoneId, {
            url: "#", 
            autoProcessQueue: false,
            maxFiles: 1,
            acceptedFiles: 'image/*',
            addRemoveLinks: true,
            dictRemoveFile: "Remove",
            
            init: function() {
                this.on("addedfile", function(file) {
                    // 1. Limpiar archivo para evitar error toJSON
                    let cleanFile = new File([file], file.name, { type: file.type });

                    // 2. Usar setTimeout para desacoplar ejecución
                    setTimeout(() => {
                        // Subimos a la propiedad 'site_logo'
                        $wire.upload('site_logo', cleanFile, 
                            () => { console.log('Logo uploaded'); }, 
                            () => { 
                                console.error('Upload failed');
                                this.removeFile(file); 
                            }
                        );
                    }, 50);
                });

                this.on("removedfile", function(file) {
                    setTimeout(() => {
                        $wire.set('site_logo', null);
                    }, 50);
                });
            }
        });
    </script>
    @endscript

    {{-- Estilos Dropzone modo oscuro --}}
    <style>
        .dropzone { padding: 1rem; }
        .dropzone .dz-preview { margin: 0; width: 100px; height: 100px; }
        .dropzone .dz-preview .dz-image { width: 100%; height: 100%; border-radius: 0.5rem; }
        .dropzone .dz-preview .dz-image img { width: 100%; height: 100%; object-fit: cover; }
        .dropzone .dz-preview { background: transparent !important; box-shadow: none !important; border: none !important; }
        .dropzone .dz-details, .dropzone .dz-success-mark, .dropzone .dz-error-mark, .dropzone .dz-progress { display: none !important; }
        .dropzone .dz-remove { margin-top: 0.5rem; font-size: 0.75rem; color: #ef4444; cursor: pointer; }
        .dropzone .dz-remove:hover { text-decoration: underline; }
        .dark .dropzone { background-color: transparent !important; }
    </style>
</section>
