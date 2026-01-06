<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Appearance')" :subheading="__('Update the appearance settings for your account')">
        
        {{-- Selector Light/Dark --}}
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
        </flux:radio.group>

        <div class="mt-6">
            {{-- INICIO DEL FORMULARIO ÚNICO --}}
            <form wire:submit.prevent="save" class="space-y-8">
                
                {{-- SECCIÓN 1: TEMA DE COLOR (Dentro del form) --}}
                <div x-data="{ theme: @entangle('site_theme').defer }">
                <flux:label class="mb-3">{{ __('App Theme') }}</flux:label>
                
                <div class="grid grid-cols-2 gap-6 sm:grid-cols-4 justify-items-center p-4 border rounded-lg border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900/50">

                    {{-- Opción 1: Yale --}}
                    <button type="button" @click="theme = 'yale'; $wire.set('site_theme', 'yale');" class="group flex flex-col items-center gap-3">
                        <div class="relative w-14 h-14 rounded-full shadow-sm transition-all duration-300 group-hover:scale-110 group-hover:shadow-md ring-2 ring-offset-2 ring-offset-white dark:ring-offset-zinc-900"
                            :class="theme === 'yale' ? 'ring-blue-800' : 'ring-transparent'"
                            style="background: linear-gradient(135deg, #FFFACD 50%, #0F4D92 50%);">
                            {{-- Check icon (Visible solo si coincide el tema) --}}
                            <div x-show="theme === 'yale'" x-transition class="absolute inset-0 flex items-center justify-center">
                                <div class="w-full h-0.5 bg-white/50 rotate-45"></div>
                            </div>
                        </div>
                        <span class="text-xs font-medium text-zinc-600 dark:text-zinc-400">Yale & Lemon</span>
                    </button>

                    {{-- Opción 2: Plum --}}
                    <button type="button" @click="theme = 'plum'; $wire.set('site_theme', 'plum');" class="group flex flex-col items-center gap-3">
                        <div class="relative w-14 h-14 rounded-full shadow-sm transition-all duration-300 group-hover:scale-110 group-hover:shadow-md ring-2 ring-offset-2 ring-offset-white dark:ring-offset-zinc-900"
                            :class="theme === 'plum' ? 'ring-fuchsia-800' : 'ring-transparent'"
                            style="background: linear-gradient(135deg, #FDFFF5 50%, #8E4585 50%);">
                            <div x-show="theme === 'plum'" x-transition class="absolute inset-0 flex items-center justify-center">
                                <div class="w-full h-0.5 bg-white/50 rotate-45"></div>
                            </div>
                        </div>
                        <span class="text-xs font-medium text-zinc-600 dark:text-zinc-400">Milk & Plum</span>
                    </button>

                    {{-- Opción 3: Noir --}}
                    <button type="button" @click="theme = 'noir'; $wire.set('site_theme', 'noir');" class="group flex flex-col items-center gap-3">
                        <div class="relative w-14 h-14 rounded-full shadow-sm transition-all duration-300 group-hover:scale-110 group-hover:shadow-md ring-2 ring-offset-2 ring-offset-white dark:ring-offset-zinc-900"
                            :class="theme === 'noir' ? 'ring-zinc-900' : 'ring-transparent'"
                            style="background: linear-gradient(135deg, #E0D5C6 50%, #1A1A1A 50%);">
                            <div x-show="theme === 'noir'" x-transition class="absolute inset-0 flex items-center justify-center">
                                <div class="w-full h-0.5 bg-white/50 rotate-45"></div>
                            </div>
                        </div>
                        <span class="text-xs font-medium text-zinc-600 dark:text-zinc-400">Oat & Noir</span>
                    </button>

                    {{-- Opción 4: Coal --}}
                    <button type="button" @click="theme = 'coal'; $wire.set('site_theme', 'coal');" class="group flex flex-col items-center gap-3">
                        <div class="relative w-14 h-14 rounded-full shadow-sm transition-all duration-300 group-hover:scale-110 group-hover:shadow-md ring-2 ring-offset-2 ring-offset-white dark:ring-offset-zinc-900"
                            :class="theme === 'coal' ? 'ring-slate-700' : 'ring-transparent'"
                            style="background: linear-gradient(135deg, #EFF7EE 50%, #36454F 50%);">
                            <div x-show="theme === 'coal'" x-transition class="absolute inset-0 flex items-center justify-center">
                                <div class="w-full h-0.5 bg-white/50 rotate-45"></div>
                            </div>
                        </div>
                        <span class="text-xs font-medium text-zinc-600 dark:text-zinc-400">Matcha & Coal</span>
                    </button>

                </div>
            </div>

                <flux:separator variant="subtle" />

                {{-- SECCIÓN 2: NOMBRE --}}
                <flux:field>
                    <flux:label>{{ __('Restaurant Name') }}</flux:label>
                    <flux:input wire:model="site_name" type="text" />
                    <flux:error name="site_name" />
                </flux:field>

                {{-- SECCIÓN 3: LOGO --}}
                <flux:field>
                    <flux:label class="mb-3">{{ __('Restaurant Logo') }}</flux:label>
                    <div class="flex flex-col gap-6 md:flex-row">
                        <div class="shrink-0 relative group">
                            <x-loading-overlay target="site_logo" />
                            @if ($site_logo)
                                <div class="h-32 w-32 rounded-lg border dark:border-zinc-700 bg-white flex items-center justify-center p-2">
                                    <img src="{{ $site_logo->temporaryUrl() }}" class="max-h-full max-w-full object-contain">
                                </div>
                            @elseif ($current_logo)
                                <div class="h-32 w-32 rounded-lg border dark:border-zinc-700 bg-white flex items-center justify-center p-2">
                                    <img src="{{ asset('storage/' . $current_logo) }}" class="max-h-full max-w-full object-contain">
                                </div>
                            @else
                                <div class="h-32 w-32 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center border dark:border-zinc-700">
                                    <span>{{ __('No Logo') }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div wire:ignore>
                                <div class="flex items-center justify-center transition border-2 border-dashed rounded-lg dropzone border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 min-h-[128px]" id="settings-dropzone">
                                    <div class="dz-message" data-dz-message>
                                        <span class="text-sm text-zinc-500">Click or drag logo</span>
                                    </div>
                                </div>
                            </div>
                            <flux:error name="site_logo" />
                        </div>
                    </div>
                </flux:field>

                {{-- SECCIÓN 4: BANNER --}}
                <flux:field>
                    <flux:label class="mb-3">{{ __('Restaurant Banner') }}</flux:label>
                    <div class="flex flex-col gap-6">
                        <div class="relative w-full group">
                            <x-loading-overlay target="site_banner" />
                            @if ($site_banner)
                                <div class="w-full overflow-hidden bg-white border rounded-lg h-44 dark:border-zinc-700">
                                    <img src="{{ $site_banner->temporaryUrl() }}" class="object-cover w-full h-full">
                                </div>
                            @elseif ($current_banner)
                                <div class="w-full overflow-hidden bg-white border rounded-lg h-44 dark:border-zinc-700">
                                    <img src="{{ asset('storage/' . $current_banner) }}" class="object-cover w-full h-full">
                                </div>
                            @else
                                <div class="flex items-center justify-center w-full bg-zinc-100 border rounded-lg h-44 dark:bg-zinc-800 dark:border-zinc-700">
                                    <span class="text-sm text-zinc-400">Default banner</span>
                                </div>
                            @endif
                        </div>
                        <div class="w-full">
                            <div wire:ignore>
                                <div class="flex items-center justify-center transition border-2 border-dashed rounded-lg dropzone border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 min-h-[100px]" id="banner-dropzone">
                                    <div class="dz-message" data-dz-message>
                                        <span class="text-sm text-zinc-500">Click or drag banner</span>
                                    </div>
                                </div>
                            </div>
                            <flux:error name="site_banner" />
                        </div>
                    </div>
                </flux:field>

                {{-- BOTÓN GUARDAR --}}
                <div class="flex justify-end pt-4">
                    <x-button type="submit" variant="primary" wire:loading.attr="disabled" class="disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="site_logo, site_banner">{{ __('Save Changes') }}</span>
                        <span wire:loading wire:target="site_logo, site_banner" class="flex items-center gap-2">
                            <x-spinner size="w-4 h-4" /> {{ __('Uploading...') }}
                        </span>
                    </x-button>
                </div>
                
                <x-toast />
            </form>
        </div>
    </x-settings.layout>

    {{-- SCRIPTS DROPZONE (Mantenlos igual que antes, solo asegúrate de que estén al final) --}}
    @assets
        <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    @endassets

    @script
    <script>
        Dropzone.autoDiscover = false;
        // ... (Tu código JS de Dropzone existente para #settings-dropzone y #banner-dropzone) ...
        // Asegúrate de copiar el JS que ya teníamos funcionando.
        
        // LOGO
        let logoDropzone = new Dropzone("#settings-dropzone", {
            url: "#", autoProcessQueue: false, maxFiles: 1, acceptedFiles: 'image/*', addRemoveLinks: true, dictRemoveFile: "Remove",
            init: function() {
                this.on("addedfile", function(file) {
                    let cleanFile = new File([file], file.name, { type: file.type });
                    setTimeout(() => { $wire.upload('site_logo', cleanFile, () => {}, () => this.removeFile(file)); }, 50);
                });
                this.on("removedfile", function(file) { setTimeout(() => $wire.set('site_logo', null), 50); });
            }
        });

        // BANNER
        let bannerDropzone = new Dropzone("#banner-dropzone", {
            url: "#", autoProcessQueue: false, maxFiles: 1, acceptedFiles: 'image/*', addRemoveLinks: true, dictRemoveFile: "Remove",
            init: function() {
                this.on("addedfile", function(file) {
                    let cleanFile = new File([file], file.name, { type: file.type });
                    setTimeout(() => { $wire.upload('site_banner', cleanFile, () => {}, () => this.removeFile(file)); }, 50);
                });
                this.on("removedfile", function(file) { setTimeout(() => $wire.set('site_banner', null), 50); });
            }
        });
    </script>
    @endscript
    
    {{-- Estilos Dropzone --}}
    <style>
        .dropzone .dz-preview { background: transparent !important; }
        .dropzone .dz-preview .dz-image img { object-fit: cover; border-radius: 0.5rem; }
        .dropzone .dz-details, .dropzone .dz-success-mark, .dropzone .dz-error-mark, .dropzone .dz-progress { display: none !important; }
        .dark .dropzone { background-color: transparent !important; }
    </style>
</section>