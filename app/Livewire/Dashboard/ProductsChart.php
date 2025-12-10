<?php

namespace App\Livewire\Dashboard;

use App\Models\Categoria;
use App\Models\DetallePedido;
use Livewire\Component;

class ProductsChart extends Component
{
    public $categoriaId = ''; // '' significa "Todas"

    public $chartData = [];

    public function mount()
    {
        $this->updateData();
    }
    public function updatedCategoriaId()
    {
        $this->updateData();
        // 3. Avisamos al frontend que hay nuevos datos
        $this->dispatch('update-products-chart', data: $this->chartData);
    }
    public function updateData()
    {
        $query = DetallePedido::query()
            ->selectRaw('producto_id, SUM(cantidad) as total')
            ->join('productos', 'detalle_pedidos.producto_id', '=', 'productos.id');

        if ($this->categoriaId) {
            $query->where('productos.categoria_id', $this->categoriaId);
        }

        $datos = $query->groupBy('producto_id')
            ->orderByDesc('total')
            ->take(5)
            ->with('producto')
            ->get();

        // Guardamos en la propiedad pública
        $this->chartData = [
            'labels' => $datos->map(fn($d) => $d->producto->nombre)->toArray(),
            'data' => $datos->pluck('total')->toArray(),
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.products-chart', [
            'categorias' => Categoria::all()
        ]);
    }
}