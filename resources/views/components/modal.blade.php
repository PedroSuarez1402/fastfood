@props(['title' => 'Modal', 'maxWidth' => '2xl'])

<div 
    x-data="{ open: @entangle($attributes->wire('model')) }" 
    x-show="open"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
    x-transition
>
    <div 
        @click.away="open = false"
        class="bg-white dark:bg-zinc-800 rounded-2xl shadow-lg w-full max-w-{{ $maxWidth }} p-6"
        x-transition
    >
        <div class="flex justify-between items-center border-b border-zinc-200 dark:border-zinc-700 pb-3 mb-4">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $title }}</h2>
            <button @click="open = false" class="text-zinc-500 hover:text-zinc-800 dark:hover:text-white">&times;</button>
        </div>

        {{ $slot }}
    </div>
</div>
