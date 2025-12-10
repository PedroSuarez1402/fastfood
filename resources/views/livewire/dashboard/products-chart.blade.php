<div class="p-6 bg-white border border-neutral-200 rounded-xl dark:border-neutral-700 dark:bg-neutral-900 shadow-sm">
    <div class="flex flex-col justify-between mb-4 md:flex-row md:items-center">
        <h3 class="text-lg font-semibold dark:text-white">Top Productos</h3>
        
        {{-- Filtro de Categoría --}}
        <select wire:model.live="categoriaId" class="mt-2 text-sm border rounded-lg md:mt-0 dark:bg-neutral-800 dark:text-white dark:border-neutral-600 py-1 px-2">
            <option value="">Todas las categorías</option>
            @foreach($categorias as $cat)
                <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="relative w-full h-64">
        <canvas id="productsChartCanvas" wire:ignore></canvas>
    </div>
</div>

@assets
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endassets

@script
<script>
    const ctx = document.getElementById('productsChartCanvas');
    let chart = null;

    function initChart(labels, data) {
        chart = new Chart(ctx, {
            type: 'bar', 
            data: {
                labels: labels,
                datasets: [{
                    label: 'Unidades Vendidas',
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                    ],
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y', // Barras horizontales
                scales: {
                    x: { beginAtZero: true, grid: { color: 'rgba(128, 128, 128, 0.1)' } },
                    y: { grid: { display: false } }
                }
            }
        });
    }

    // 1. Carga inicial usando la propiedad pública
    // Verificamos si hay datos antes de inicializar para evitar errores si está vacío
    if ($wire.chartData && $wire.chartData.labels) {
        initChart($wire.chartData.labels, $wire.chartData.data);
    }

    // 2. Escuchar el evento de actualización
    $wire.on('update-products-chart', (event) => {
        // Obtenemos los datos del evento
        const datos = event.data || event[0];

        if (chart) {
            chart.data.labels = datos.labels;
            chart.data.datasets[0].data = datos.data;
            chart.update();
        } else {
            // Si por alguna razón el chart no existía (ej. carga inicial vacía), lo creamos
            initChart(datos.labels, datos.data);
        }
    });
</script>
@endscript