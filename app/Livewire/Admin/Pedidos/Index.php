<?php

namespace App\Livewire\Admin\Pedidos;

use App\Models\Mesa;
use App\Models\Pedido;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $mesas;
    public $showAsignarMesaModal = false;
    public $showVerDetalleModal = false;
    public $pedidoSeleccionado;
    public $mesa_id;

    public function mount()
    {
        $this->mesas = Mesa::all();
    }
    /* Asignar mesa */
    public function openAsignarMesaModal($id)
    {
        $this->pedidoSeleccionado = Pedido::find($id);

        if (!$this->pedidoSeleccionado) {
            return;
        }
        $this->mesa_id = $this->pedidoSeleccionado->mesa_id;
        $this->showAsignarMesaModal = true;
    }
    /* Ver detalle modal */
    public function openVerDetalleModal($id)
    {
        $this->pedidoSeleccionado = Pedido::with(['detalles.producto', 'mesa'])->find($id);

        if (!$this->pedidoSeleccionado) {
            return;
        }

        $this->showAsignarMesaModal = false;
        $this->showVerDetalleModal = true;
    }

    public function asignarMesa()
    {
        $this->validate([
            'mesa_id' => 'required|exists:mesas,id'
        ]);

        $this->pedidoSeleccionado->update([
            'mesa_id' => $this->mesa_id,
            'estado' => 'servido',
        ]);

        $this->showAsignarMesaModal = false;
        session()->flash('success', 'Mesa asignada y pedido marcado como servido.');

        // Reset paginación para refrescar vista
        $this->resetPage();
    }
    public function render()
    {
        $pedidos = Pedido::with(['detalles.producto', 'mesa'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.admin.pedidos.index', [
            'pedidos' => $pedidos,
        ]);
    }
}
