@props([
    'variant' => 'primary', // primary, secondary, ghost, danger
    'size' => 'md',         // sm, md, lg
    'type' => 'button',
])

@php
    $base = "inline-flex items-center justify-center font-medium rounded-lg transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2";

    $variants = [
        'primary' => 'bg-emerald-600 text-white hover:bg-emerald-700 focus:ring-emerald-500',
        'secondary' => 'bg-zinc-100 text-zinc-800 hover:bg-zinc-200 focus:ring-zinc-300 dark:bg-zinc-700 dark:text-zinc-100 dark:hover:bg-zinc-600',
        'ghost' => 'bg-transparent text-zinc-600 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-700',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-base',
        'lg' => 'px-5 py-3 text-lg',
    ];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "$base {$variants[$variant]} {$sizes[$size]}"]) }}>
    {{ $slot }}
</button>
