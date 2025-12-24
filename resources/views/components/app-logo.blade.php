<div class="flex items-center gap-2">
    @if (!empty($globalSiteLogo))
        {{-- CASO 1: Hay un logo subido por el admin --}}
        <img src="{{ asset('storage/' . $globalSiteLogo) }}" alt="{{ $globalSiteName }}" class="size-8 object-contain">
    @else
        {{-- CASO 2: No hay logo, usamos el ícono por defecto (Hamburguesa) --}}
        <div
            class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
        </div>
    @endif

    {{-- Nombre del sitio dinámico --}}
    <div class="ms-1 grid flex-1 text-start text-sm">
        <span class="mb-0.5 truncate leading-tight font-semibold">
            {{ $globalSiteName }}
        </span>
    </div>
</div>
