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
    public $nombreCliente = '';

    // NUEVO: Método mount para cargar la sesión al refrescar la página
    public function mount()
    {
        // Recupera 'cart' de la sesión. Si no existe, devuelve un array vacío [].
        $this->cart = session()->get('cart', []);

        $this->nombreCliente = session()->get('nombre_cliente', '');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function addToCart($productId)
    {
        if(isset($this->cart[$productId])) {
            $this->cart[$productId]['cantidad']++;
            $this->saveCartToSession(); // NUEVO: Guardar cambios
            return;
        }

        $producto = Producto::findOrFail($productId);
        
        $this->cart[$productId] = [
            'id' => $producto->id,
            'nombre' => $producto->nombre,
            'precio' => $producto->precio,
            'cantidad' => 1,
        ];

        $this->saveCartToSession(); // NUEVO: Guardar cambios
    }

    public function aumentarCantidad($id)
    {
        $this->cart[$id]['cantidad']++;
        $this->saveCartToSession(); // NUEVO: Guardar cambios
    }

    public function disminuirCantidad($id)
    {
        if ($this->cart[$id]['cantidad'] > 1) {
            $this->cart[$id]['cantidad']--;
        } else {
            // Opcional: si baja de 1, podrías eliminarlo, 
            // pero tu lógica actual solo detiene en 1, así que lo dejamos así.
        }
        $this->saveCartToSession(); // NUEVO: Guardar cambios
    }

    public function eliminarProducto($id)
    {
        unset($this->cart[$id]);
        $this->saveCartToSession(); // NUEVO: Guardar cambios
    }

    // NUEVO: Método auxiliar para no repetir código
    // Guarda el estado actual del array $cart en la sesión del navegador
    public function saveCartToSession()
    {
        session()->put('cart', $this->cart);
    }

    public function updatedNombreCliente()
    {
        session()->put('nombre_cliente', $this->nombreCliente);
    }

    public function finalizarPedido()
    {
        if (empty($this->cart)) {
            return;
        }

        $this->validate([
            'nombreCliente' => 'required|string|min:3|max:50',
        ], [
            'nombreCliente.required' => 'Por favor, dinos a nombre de quién es el pedido.',
            'nombreCliente.min' => 'El nombre es muy corto.',
        ]);

        $pedido = Pedido::create([
            'mesa_id' => null,
            'nombre_cliente' => $this->nombreCliente, // 5. NUEVO: Guardar en BD
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

        // Vaciar carrito (variable local)
        $this->cart = [];
        $this->nombreCliente = '';
        
        // NUEVO: Borrar carrito de la sesión
        session()->forget([ 'cart', 'nombre_cliente' ]);

        // Cerrar sidebar
        $this->sidebarOpen = false;

        session()->flash('success', 'Pedido creado correctamente');
    }

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

        $productos = $query->paginate(20);

        return view('livewire.public.products', [
            'categorias' => $categorias,
            'productos'  => $productos,
        ]);
    }
}