@props(['tools' => null])

<div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden flex flex-col">
    
    {{-- Barra de Herramientas (Búsqueda, Filtros) --}}
    @if ($tools)
        <div class="p-4 border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-800/20">
            {{ $tools }}
        </div>
    @endif

    {{-- Tabla Responsive --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-zinc-600 dark:text-zinc-300">
            
            {{-- Encabezado de Tabla --}}
            <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-900 dark:text-zinc-100 uppercase font-semibold text-xs tracking-wider border-b border-zinc-200 dark:border-zinc-700">
                <tr>
                    {{ $header }}
                </tr>
            </thead>

            {{-- Cuerpo de Tabla --}}
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                {{ $slot }}
            </tbody>
            
        </table>
    </div>

    {{-- Paginación (Slot opcional footer) --}}
    @if (isset($footer))
        <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50">
            {{ $footer }}
        </div>
    @endif
</div>