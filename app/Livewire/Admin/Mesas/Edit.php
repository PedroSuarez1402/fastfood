<?php

namespace App\Livewire\Admin\Mesas;

use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\DetallePedido;
use Livewire\Component;

class Edit extends Component
{
    public $mesa;
    public $pedido;
    public $productos;

    // Para agregar productos
    public $producto_id;
    public $cantidad = 1;

    public function mount(Mesa $mesa)
    {
        $this->mesa = $mesa;

        // Cargar pedido activo
        $this->pedido = $this->mesa->pedidos()
            ->where('estado', 'servido')
            ->with('detalles.producto')
            ->first();

        $this->productos = Producto::where('disponible', true)->get();
    }

    /** Agregar producto al pedido */
    public function agregarProducto()
    {
        if (!$this->pedido) {
            // Crear pedido si no existe
            $this->pedido = Pedido::create([
                'mesa_id' => $this->mesa->id,
                'nombre_cliente' => 'Cliente Mesa ' . $this->mesa->numero,
                'estado' => 'pendiente',
                'total' => 0,
            ]);

            $this->mesa->update(['estado' => 'ocupada']);
        }

        $producto = Producto::find($this->producto_id);

        if (!$producto) return;

        // Si ya existe el detalle → aumentar cantidad
        $detalle = $this->pedido->detalles()->where('producto_id', $producto->id)->first();

        if ($detalle) {
            $detalle->update([
                'cantidad' => $detalle->cantidad + $this->cantidad,
                'subtotal' => ($detalle->cantidad + $this->cantidad) * $producto->precio,
            ]);
        } else {
            DetallePedido::create([
                'pedido_id' => $this->pedido->id,
                'producto_id' => $producto->id,
                'cantidad' => $this->cantidad,
                'subtotal' => $producto->precio * $this->cantidad,
            ]);
        }

        $this->actualizarTotal();
        $this->refrescar();
    }

    /** Actualizar cantidad de un producto */
    public function actualizarCantidad($detalleId, $nuevaCantidad)
    {
        $detalle = DetallePedido::find($detalleId);
        if (!$detalle) return;

        $detalle->update([
            'cantidad' => $nuevaCantidad,
            'subtotal' => $nuevaCantidad * $detalle->producto->precio,
        ]);

        $this->actualizarTotal();
        $this->refrescar();
    }

    /** Eliminar un producto del pedido */
    public function eliminarDetalle($detalleId)
    {
        DetallePedido::where('id', $detalleId)->delete();
        $this->actualizarTotal();
        $this->refrescar();
    }

    /** Recalcular total del pedido */
    private function actualizarTotal()
    {
        if ($this->pedido) {
            $total = $this->pedido->detalles()->sum('subtotal');
            $this->pedido->update(['total' => $total]);
        }
    }

    /** Refrescar datos */
    private function refrescar()
    {
        $this->pedido->refresh();
        $this->mesa->refresh();
    }

    public function render()
    {
        return view('livewire.admin.mesas.edit');
    }
}
