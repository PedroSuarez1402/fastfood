<?php

namespace App\Livewire\Admin\Productos;

use App\Models\Producto;
use Livewire\Component;

class Show extends Component
{
    public Producto $producto;
    public function mount(Producto $producto)
    {
        $this->producto = $producto;
    }
    public function toggleDisponibilidad()
    {
        $this->producto->disponible = !$this->producto->disponible;
        $this->producto->save();

        session()->flash('success', $this->producto->disponible
            ? 'El producto ahora está disponible.'
            : 'El producto fue marcado como no disponible.');

        // Recargar data
        $this->producto->refresh();
    }
    public function render()
    {
        return view('livewire.admin.productos.show');
    }
}
