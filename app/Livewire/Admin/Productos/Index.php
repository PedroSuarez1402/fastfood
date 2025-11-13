<?php

namespace App\Livewire\Admin\Productos;

use App\Models\Categoria;
use App\Models\Producto;
use Livewire\Component;

class Index extends Component
{
    public $categorias;
    public $categoriaSeleccionada = null;
    public $productos = [];

    public function mount()
    {
        // Cargar las categorias
        $this->categorias = Categoria::with('productos')->get();
        // Seleccionar la primera categoria
        if ($this->categorias->isNotEmpty())
        {
            $this->categoriaSeleccionada = $this->categorias->first()->id;
            $this->actualizarProductos();
        }
    }
    public function seleccionarCategoria($categoriaId)
    {
        $this->categoriaSeleccionada = $categoriaId;
        $this->actualizarProductos();
    }

    public function actualizarProductos()
    {
        $this->productos = Producto::where('categoria_id', $this->categoriaSeleccionada)->with('categoria')->get();
    }
    public function render()
    {
        return view('livewire.admin.productos.index');
    }
}
