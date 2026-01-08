@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded border-zinc-300 dark:border-zinc-700 text-emerald-600 shadow-sm focus:ring-emerald-500 dark:bg-zinc-800 dark:focus:ring-offset-zinc-900']) !!} type="checkbox">