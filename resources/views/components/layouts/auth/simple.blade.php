<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
    <div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
        <div class="flex w-full max-w-sm flex-col gap-2">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>

                {{-- Lógica para mostrar Logo Grande o Ícono --}}
                @if (!empty($globalSiteLogo))
                    {{-- CASO 1: Imagen personalizada subida por el admin --}}
                    {{-- Le damos una altura mayor (h-20 = 80px) y ajuste automático --}}
                    <img src="{{ asset('storage/' . $globalSiteLogo) }}" alt="{{ $globalSiteName ?? config('app.name') }}"
                        class="h-40 w-auto object-contain mb-2 hover:opacity-90 transition">
                @else
                    {{-- CASO 2: Ícono por defecto (Hamburguesa/Pizza) --}}
                    <span
                        class="flex h-16 w-16 mb-1 items-center justify-center rounded-xl bg-zinc-100 dark:bg-zinc-800 text-orange-500">
                        <x-app-logo-icon class="size-10 fill-current" />
                    </span>
                @endif

                {{-- Opcional: Mostrar el nombre del restaurante debajo del logo --}}
                <span class="text-xl font-bold tracking-tight text-zinc-900 dark:text-white">
                    {{ $globalSiteName ?? config('app.name') }}
                </span>
            </a>
            <div class="flex flex-col gap-6">
                {{ $slot }}
            </div>
        </div>
    </div>
    @fluxScripts
</body>

</html>
