<x-layouts.app :title="__('Dashboard')">
    <div class="flex flex-col flex-1 w-full h-full gap-6 rounded-xl min-w-0">

        {{-- GRID DE WIDGETS (Ahora con Gráficos) --}}
        <div class="grid gap-6 auto-rows-min grid-cols-1 md:grid-cols-2 xl:grid-cols-3">

            {{-- 1. Gráfico: Estado de Mesas --}}
            <div class="relative flex flex-col justify-between p-6 bg-white border border-neutral-200 rounded-xl dark:border-neutral-700 dark:bg-neutral-900 shadow-sm overflow-hidden">
                <a href="{{route('admin.mesas.index')}}" class="flex flex-col items-center h-full">
                    <h3 class="w-full mb-4 text-lg font-semibold text-left dark:text-white">Estado de Mesas</h3>
                    <div class="relative w-full h-40 flex justify-center items-center">
                        <canvas id="chartMesas"></canvas>
                        {{-- Texto central flotante (Opcional) --}}
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <span class="text-3xl font-bold text-gray-400 dark:text-gray-500">{{ $mesasOcupadas + $mesasDisponibles }}</span>
                        </div>
                    </div>
                    {{-- Mini leyenda manual (Opcional, para no ensuciar el gráfico) --}}
                    <div class="flex justify-center gap-4 mt-4 text-sm">
                        <div class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-500"></span> <span class="text-gray-500">Ocupada</span></div>
                        <div class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-500"></span> <span class="text-gray-500">Disponible</span></div>
                    </div>
                </a>
            </div>

            {{-- 2. Tarjeta: Producto Top (Mantendremos texto destacado porque es un nombre) --}}
            <div class="relative flex flex-col justify-center p-6 bg-white border border-neutral-200 rounded-xl dark:border-neutral-700 dark:bg-neutral-900 shadow-sm">
                <a href="{{route('admin.productos.index')}}" class="flex flex-col h-full justify-between">
                    <div>
                        <h3 class="mb-2 text-lg font-semibold dark:text-white">Producto Estrella ⭐</h3>
                        @if ($productoTop)
                            <p class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-pink-500 break-words leading-tight">
                                {{ $productoTop->nombre }}
                            </p>
                        @else
                            <p class="italic text-neutral-500 dark:text-neutral-400">Sin datos</p>
                        @endif
                    </div>
                    
                    <div class="mt-4">
                        <p class="text-3xl font-bold text-neutral-800 dark:text-neutral-200">
                            {{ $productoMasVendido->total ?? 0 }}
                        </p>
                        <p class="text-sm text-neutral-500">unidades vendidas hoy</p>
                    </div>
                </a>
            </div>

            {{-- 3. Gráfico: Pedidos Activos --}}
            <div class="relative flex flex-col justify-between p-6 bg-white border border-neutral-200 rounded-xl dark:border-neutral-700 dark:bg-neutral-900 shadow-sm overflow-hidden">
                <a href="{{route('admin.pedidos.index')}}" class="flex flex-col h-full">
                    <h3 class="w-full mb-4 text-lg font-semibold text-left dark:text-white">Pedidos Hoy</h3>
                    
                    {{-- Contenedor con Altura Fija --}}
                    <div class="relative w-full h-40 flex justify-center items-center">
                        <canvas id="chartPedidosStats"></canvas>
                        
                        {{-- Texto central --}}
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <span class="text-3xl font-bold text-orange-500">{{ $pedidosActivos }}</span>
                        </div>
                    </div>

                    {{-- Mini leyenda manual --}}
                    <div class="flex justify-center gap-4 mt-4 text-sm">
                        <div class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-orange-500"></span> <span class="text-gray-500">Activos</span></div>
                        <div class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-blue-500"></span> <span class="text-gray-500">Pagados</span></div>
                    </div>
                </a>
            </div>

        </div>

        {{-- GRID DE GRÁFICOS GRANDES --}}
        {{-- En móvil 1 columna, en desktop (lg) 2 columnas --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-2">
            
            <div class="p-6 bg-white border border-neutral-200 rounded-xl dark:border-neutral-700 dark:bg-neutral-900 shadow-sm">
                <h3 class="text-lg font-semibold dark:text-white mb-4">Ventas últimos 7 días</h3>
                {{-- Contenedor relativo para que ChartJS respete el tamaño --}}
                <div class="relative h-64 w-full">
                    <canvas id="graficoVentas"></canvas>
                </div>
            </div>

            <div class="p-6 bg-white border border-neutral-200 rounded-xl dark:border-neutral-700 dark:bg-neutral-900 shadow-sm">
                <h3 class="text-lg font-semibold dark:text-white mb-4">Top 5 productos vendidos</h3>
                <div class="relative h-64 w-full">
                    <canvas id="graficoProductos"></canvas>
                </div>
            </div>

        </div>
    </div>
</x-layouts.app>

<script>
document.addEventListener('livewire:navigated', () => {

    /* CONFIGURACIÓN COMÚN (Para que se vean bien en las cards pequeñas) */
    const commonDoughnutOptions = {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '75%', // Hace el agujero del centro más grande
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                enabled: true
            }
        }
    };

    /* 1. CHART: MESAS */
    const ctxMesas = document.getElementById('chartMesas');
    if (ctxMesas) {
        new Chart(ctxMesas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Ocupadas', 'Disponibles'],
                datasets: [{
                    data: [{{ $mesasOcupadas }}, {{ $mesasDisponibles }}],
                    backgroundColor: ['#ef4444', '#22c55e'], // Rojo, Verde
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: commonDoughnutOptions
        });
    }

    /* 2. CHART: PEDIDOS (Activos vs Pagados) */
    const ctxPedidos = document.getElementById('chartPedidosStats');
    if (ctxPedidos) {
        new Chart(ctxPedidos.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Activos', 'Pagados Hoy'],
                datasets: [{
                    data: [{{ $pedidosActivos }}, {{ $pedidosPagadosHoy }}],
                    backgroundColor: ['#f97316', '#3b82f6'], // Naranja, Azul
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: commonDoughnutOptions
        });
    }

    /* 3. CHART: VENTAS 7 DÍAS */
    const ctxVentas = document.getElementById('graficoVentas');
    if (ctxVentas) {
        new Chart(ctxVentas.getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($labelsVentas),
                datasets: [{
                    label: 'Ventas ($)',
                    data: @json($datosVentas),
                    borderWidth: 3,
                    borderColor: '#6366f1', // Indigo
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: { 
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(128, 128, 128, 0.1)' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    /* 4. CHART: TOP PRODUCTOS (Tu código existente) */
    const ctxProductos = document.getElementById('graficoProductos');
    if (ctxProductos) {
        new Chart(ctxProductos.getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($labelsProductos),
                datasets: [{
                    label: 'Unidades',
                    data: @json($datosProductos),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                    ],
                    borderRadius: 4
                }]
            },
            options: { 
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                scales: {
                    x: { beginAtZero: true, grid: { color: 'rgba(128, 128, 128, 0.1)' } },
                    y: { grid: { display: false } }
                }
            }
        });
    }

}, { once: true });
</script>