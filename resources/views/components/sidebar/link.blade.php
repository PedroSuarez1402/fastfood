@props(['href', 'active' => false, 'icon' => null])

@php
    $classes = $active
        ? 'flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg bg-zinc-200 text-zinc-900 dark:bg-zinc-800 dark:text-white transition-colors'
        : 'flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-white transition-colors';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if ($icon)
        {{-- Aquí asumimos que usas FontAwesome o Heroicons. Ajusta según tu librería --}}
        <i class="{{ $icon }} w-5 h-5 flex justify-center items-center"></i>
    @endif
    <span>{{ $slot }}</span>
</a>
