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
    public $search = '';

    public function mount()
    {
        $this->mesas = Mesa::where('estado', 'disponible')->get();
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }
    /* Asignar mesa */
    public function openAsignarMesaModal($id)
    {
        $this->pedidoSeleccionado = Pedido::with('mesa')->find($id);

        if (!$this->pedidoSeleccionado) return;

        // Traer solo mesas disponibles + la mesa actual del pedido
        $this->mesas = Mesa::where('estado', 'disponible')
            ->orWhere('id', $this->pedidoSeleccionado->mesa_id)
            ->get();

        $this->mesa_id = $this->pedidoSeleccionado->mesa_id;
        $this->showVerDetalleModal = false;
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

        $pedido = $this->pedidoSeleccionado;

        // Si el pedido ya tenía mesa previamente → liberarla
        if ($pedido->mesa_id) {
            Mesa::where('id', $pedido->mesa_id)
                ->update(['estado' => 'disponible']);
        }

        // Asignar nueva mesa y marcarla ocupada
        Mesa::where('id', $this->mesa_id)
            ->update(['estado' => 'ocupada']);

        // Actualizar el pedido
        $pedido->update([
            'mesa_id' => $this->mesa_id,
            'estado' => 'servido',
        ]);

        $this->showAsignarMesaModal = false;
        session()->flash('success', 'Mesa asignada y pedido marcado como servido.');

        $this->resetPage();
    }
    public function marcarComoPagado($id)
    {
        $pedido = Pedido::find($id);

        if (!$pedido) return;

        // liberar mesa
        if ($pedido->mesa_id) {
            Mesa::where('id', $pedido->mesa_id)
                ->update(['estado' => 'disponible']);
        }

        // actualizar pedido
        $pedido->update([
            'estado' => 'pagado',
            'mesa_id' => null // ya no está en uso
        ]);

        session()->flash('success', 'Pedido pagado y mesa liberada.');
        $this->resetPage();
    }
    public function render()
    {
        $pedidos = Pedido::with(['detalles.producto', 'mesa'])
            ->where(function ($query) {
                // Si hay algo en el buscador, aplicamos estos filtros
                if ($this->search) {
                    $query->where('codigo_ticket', 'like', '%' . $this->search . '%')
                        ->orWhere('nombre_cliente', 'like', '%' . $this->search . '%')
                        ->orWhere('estado', 'like', '%' . $this->search . '%')
                        // Buscamos también dentro de la relación 'mesa'
                        ->orWhereHas('mesa', function ($q) {
                            $q->where('numero', 'like', '%' . $this->search . '%');
                        });
                }
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.admin.pedidos.index', [
            'pedidos' => $pedidos,
        ]);
    }
}
