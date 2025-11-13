@props(['type' => 'info']) {{-- success, error, warning, info --}}

@php
    $base = "flex items-center gap-3 rounded-lg p-3 border text-sm font-medium";
    $styles = [
        'success' => 'bg-emerald-100 text-emerald-800 border-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-300 dark:border-emerald-800',
        'error' => 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/40 dark:text-red-300 dark:border-red-800',
        'warning' => 'bg-yellow-100 text-yellow-800 border-yellow-200 dark:bg-yellow-900/40 dark:text-yellow-300 dark:border-yellow-800',
        'info' => 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/40 dark:text-blue-300 dark:border-blue-800',
    ];
@endphp

<div {{ $attributes->merge(['class' => "$base {$styles[$type]}"]) }}>
    {{ $slot }}
</div>
