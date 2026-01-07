@props(['color' => 'zinc'])

@php
    $colors = [
        'zinc'   => 'bg-zinc-100 text-zinc-800 dark:bg-zinc-700 dark:text-zinc-300',
        'red'    => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
        'green'  => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
        'blue'   => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
        'orange' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300',
        'purple' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300',
        'yellow' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
    ];

    // Si el color no existe en el array, usamos 'zinc' por defecto
    $classes = $colors[$color] ?? $colors['zinc'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium $classes"]) }}>
    {{ $slot }}
</span>