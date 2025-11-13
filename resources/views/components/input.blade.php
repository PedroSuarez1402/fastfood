@props(['type' => 'text'])

<input 
    type="{{ $type }}" 
    {{ $attributes->merge([
        'class' => 'w-full border border-zinc-300 dark:border-zinc-700 rounded-lg p-2.5 bg-white dark:bg-zinc-800 focus:ring-emerald-500 focus:border-emerald-500 text-zinc-900 dark:text-zinc-100'
    ]) }}
/>
