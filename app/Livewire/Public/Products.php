<?php

namespace App\Livewire\Public;

use App\Models\Categoria;
use App\Models\Producto;
use Livewire\Component;

class Products extends Component
{
    public $search = '';
    public $categoriaSeleccionada = null;

    public function seleccionarCategoria($categoriaId)
    {
        $this->categoriaSeleccionada = $categoriaId;
    }
    public function render()
    {
        // Traer categorías ordenadas
        $categorias = Categoria::orderBy('nombre')->get();

        // Query inicial
        $query = Producto::where('disponible', true);

        // Filtro por categoría
        if ($this->categoriaSeleccionada) {
            $query->where('categoria_id', $this->categoriaSeleccionada);
        }

        // Filtro por búsqueda
        if ($this->search) {
            $query->where('nombre', 'like', '%' . $this->search . '%');
        }

        $productos = $query->get();

        return view('livewire.public.products', [
            'categorias' => $categorias,
            'productos'  => $productos,
        ]);
    }
}
