@props(['title', 'icon' => null, 'active' => false])

<div x-data="{ open: {{ $active ? 'true' : 'false' }} }" class="space-y-1">
    <button @click="open = !open" type="button"
        class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-lg transition-colors
            text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-white">

        <div class="flex items-center gap-3">
            @if ($icon)
                <i class="{{ $icon }} w-5 h-5 flex justify-center items-center"></i>
            @endif
            <span>{{ $title }}</span>
        </div>

        {{-- Icono Chevron --}}
        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open" x-collapse style="display: none;"
        class="pl-4 space-y-1 border-l border-zinc-200 dark:border-zinc-700 ml-3">
        {{ $slot }}
    </div>
</div>
