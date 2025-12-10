<div class="p-6 bg-white border border-neutral-200 rounded-xl dark:border-neutral-700 dark:bg-neutral-900 shadow-sm">
    <div class="flex flex-col justify-between mb-4 md:flex-row md:items-center">
        <h3 class="text-lg font-semibold dark:text-white">Reporte de Ventas</h3>
        
        {{-- Filtros de Fecha --}}
        <div class="flex gap-2 mt-2 md:mt-0">
            {{-- wire:model.live.debounce.500ms evita recargas excesivas al escribir --}}
            <input type="date" wire:model.live="start" class="px-2 py-1 text-sm border rounded-lg dark:bg-neutral-800 dark:text-white dark:border-neutral-600">
            <span class="text-gray-500">-</span>
            <input type="date" wire:model.live="end" class="px-2 py-1 text-sm border rounded-lg dark:bg-neutral-800 dark:text-white dark:border-neutral-600">
        </div>
    </div>

    <div class="relative w-full h-64">
        <canvas id="salesChartCanvas" wire:ignore></canvas>
    </div>
</div>

@assets
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endassets

@script
<script>
    const ctx = document.getElementById('salesChartCanvas');
    let chart = null;

    function initChart(labels, data) {
        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Ventas ($)',
                    data: data,
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 3
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

    // 1. Carga inicial: Aquí SI podemos leer $wire.chartData porque es pública
    initChart($wire.chartData.labels, $wire.chartData.data);

    // 2. Escuchar evento de actualización del PHP
    $wire.on('update-sales-chart', (event) => {
        // En Livewire 3 los eventos vienen a veces encapsulados, extraemos 'data' o el primer argumento
        const datos = event.data || event[0]; 
        
        if (chart) {
            chart.data.labels = datos.labels;
            chart.data.datasets[0].data = datos.data;
            chart.update();
        }
    });
</script>
@endscript