@props(['title', 'description' => null])

<div {{ $attributes->merge(['class' => 'flex flex-col md:flex-row justify-between items-start md:items-center gap-4']) }}>
    <div>
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">
            {{ $title }}
        </h1>
        
        @if ($description)
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                {{ $description }}
            </p>
        @endif
    </div>
    {{-- Aquí irán los botones o acciones que pases dentro del componente --}}
    @if (!$slot->isEmpty())
        <div class="flex items-center gap-3">
            {{ $slot }}
        </div>
    @endif
</div>