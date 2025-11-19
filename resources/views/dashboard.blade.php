<x-layouts.app :title="__('Dashboard')">

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        {{-- GRID DE WIDGETS --}}
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">

            {{-- Mesas Ocupadas / Disponibles --}}
            <div
                class="relative aspect-video rounded-xl border border-neutral-200 dark:border-neutral-700 p-6
                        bg-white dark:bg-neutral-900 flex flex-col justify-center">
                <a href="{{route('admin.mesas.index')}}">
                    <h3 class="text-lg font-semibold dark:text-white">Estado de Mesas</h3>

                    <p class="mt-4 text-3xl font-bold text-blue-600 dark:text-blue-400">
                        {{ $mesasOcupadas }} <span class="text-sm font-normal">ocupadas</span>
                    </p>

                    <p class="text-green-600 dark:text-green-400 text-xl font-bold">
                        {{ $mesasDisponibles }} <span class="text-sm font-normal">disponibles</span>
                    </p>
                </a>
            </div>

            {{-- Ventas del día --}}
            <div
                class="relative aspect-video rounded-xl border border-neutral-200 dark:border-neutral-700 p-6
                        bg-white dark:bg-neutral-900 flex flex-col justify-center">
                <a href="{{route('admin.productos.index')}}">
                @if ($productoTop)
                    <h3 class="text-lg font-semibold dark:text-white mb-4">Producto más vendido</h3>

                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                        {{ $productoTop->nombre }}
                    </p>

                    <p class="text-xl font-semibold text-neutral-700 dark:text-neutral-300 mt-2">
                        {{ $productoMasVendido->total }} unidades vendidas
                    </p>
                @else
                    <p class="text-neutral-500 dark:text-neutral-400 italic">No hay ventas registradas.</p>
                @endif
                </a>
            </div>

            {{-- Pedidos Activos --}}
            <div
                class="relative aspect-video rounded-xl border border-neutral-200 dark:border-neutral-700 p-6
                        bg-white dark:bg-neutral-900 flex flex-col justify-center">
                <a href="{{route('admin.pedidos.index')}}">
                    <h3 class="text-lg font-semibold dark:text-white">Pedidos Activos</h3>

                    <p class="mt-4 text-4xl font-bold text-orange-500 dark:text-orange-300">
                        {{ $pedidosActivos }}
                    </p>
                </a>
            </div>
        </div>

        

    </div>

</x-layouts.app>
