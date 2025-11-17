<?php

namespace App\Livewire\Public;

use App\Models\Categoria;
use App\Models\Pedido;
use App\Models\Producto;
use Livewire\Component;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';
    public $search = '';
    public $categoriaSeleccionada = null;
    public $cart = [];
    public $sidebarOpen = false;

    protected $listeners = ['addToCart'];

    // Resetear la paginación si cambia el search
    public function updatedSearch()
    {
        $this->resetPage();
    }
    //Agregar al carrito
    public function addToCart($data)
    {
        $productId = $data['id'];

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['cantidad']++;
        } else {
            $producto = Producto::findOrFail($productId);
            $this->cart[$productId] = [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'cantidad' => 1,
            ];
        }
    }
    public function aumentarCantidad($id)
    {
        $this->cart[$id]['cantidad']++;
    }
    public function disminuirCantidad($id)
    {
        if ($this->cart[$id]['cantidad'] > 1) {
            $this->cart[$id]['cantidad']--;
        }
    }
    public function eliminarProducto($id)
    {
        unset($this->cart[$id]);
    }
    public function finalizarPedido()
    {
        if (empty($this->cart)) {
            return;
        }

        $pedido = Pedido::create([
            'mesa_id' => null, // si luego quieres seleccionar mesa se cambia aquí
            'estado' => 'pendiente',
            'total' => 0,
        ]);

        $total = 0;

        foreach ($this->cart as $item) {
            $subtotal = $item['precio'] * $item['cantidad'];
            $total += $subtotal;

            $pedido->detalles()->create([
                'producto_id' => $item['id'],
                'cantidad' => $item['cantidad'],
                'subtotal' => $subtotal,
            ]);
        }

        $pedido->update(['total' => $total]);

        // Vaciar carrito
        $this->cart = [];

        session()->flash('success', 'Pedido creado correctamente');
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
