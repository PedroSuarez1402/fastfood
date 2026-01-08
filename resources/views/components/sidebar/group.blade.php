@props(['heading'])

<div class="space-y-1">
    @if ($heading)
        <div class="px-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-2 mt-4">
            {{ $heading }}
        </div>
    @endif
    {{ $slot }}
</div>
