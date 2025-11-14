<?php

namespace App\Livewire\Public;

use App\Models\Categoria;
use App\Models\Producto;
use Livewire\Component;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';
    public $search = '';
    public $categoriaSeleccionada = null;

    // Resetear la paginación si cambia el search
    public function updatedSearch()
    {
        $this->resetPage();
    }

    // Resetear la paginación si cambia la categoría
    public function seleccionarCategoria($categoriaId)
    {
        $this->categoriaSeleccionada = $categoriaId;
        $this->resetPage();
    }
    public function render()
    {
        $categorias = Categoria::orderBy('nombre')->get();

        $query = Producto::where('disponible', true);

        if ($this->categoriaSeleccionada) {
            $query->where('categoria_id', $this->categoriaSeleccionada);
        }

        if ($this->search !== '') {
            $query->where('nombre', 'like', "%{$this->search}%");
        }

        // PAGINACIÓN AQUÍ 
        $productos = $query->paginate(20);

        return view('livewire.public.products', [
            'categorias' => $categorias,
            'productos'  => $productos,
        ]);
    }
}
