@props(['target'])

<div wire:loading.flex wire:target="{{ $target }}"
     class="absolute inset-0 z-10 flex items-center justify-center rounded-lg bg-white/70 dark:bg-black/70 backdrop-blur-[2px]">
    {{-- Asegúrate de que el componente x-spinner también exista --}}
    <x-spinner size="w-8 h-8" color="text-emerald-600" />
</div>