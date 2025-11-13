@props(['for' => null])

<label 
    @if($for) for="{{ $for }}" @endif
    {{ $attributes->merge([
        'class' => 'block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1'
    ]) }}
>
    {{ $slot }}
</label>
