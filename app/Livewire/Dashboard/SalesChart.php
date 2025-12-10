<?php

namespace App\Livewire\Dashboard;

use App\Models\Pedido;
use Carbon\Carbon;
use Livewire\Component;

class SalesChart extends Component
{
    public $start;
    public $end;

    public $chartData = [];
    public function mount()
    {
        $this->start = now()->subDays(30)->format('Y-m-d');
        $this->end = now()->format('Y-m-d');

        $this->updateData();
    }
    public function updated()
    {
        $this->updateData();

        $this->dispatch('update-sales-chart', data: $this->chartData);
    }

    public function updateData()
    {
        $ventas = Pedido::selectRaw('fecha_pedido, SUM(total) as total')
            ->where('estado', 'pagado')
            ->whereBetween('fecha_pedido', [$this->start, $this->end])
            ->groupBy('fecha_pedido')
            ->orderBy('fecha_pedido')
            ->get();

        $this->chartData = [
            'labels' => $ventas->pluck('fecha_pedido')->map(fn($f) => Carbon::parse($f)->format('d M'))->toArray(),
            'data' => $ventas->pluck('total')->toArray(),
        ];
    }
    public function render()
    {
        return view('livewire.dashboard.sales-chart');
    }
}
