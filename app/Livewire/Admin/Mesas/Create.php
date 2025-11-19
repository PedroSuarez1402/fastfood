<?php

namespace App\Livewire\Admin\Mesas;

use App\Models\Mesa;
use Livewire\Component;

class Create extends Component
{
    public $numero;
    public $capacidad;
    public $estado = 'disponible';

    protected $rules = [
        'numero' => 'required|numeric|min:1|unique:mesas,numero',
        'capacidad' => 'required|numeric|min:1',
        'estado' => 'required|in:disponible,ocupada',
    ];

    public function save()
    {
        $this->validate();

        Mesa::create([
            'numero' => $this->numero,
            'capacidad' => $this->capacidad,
            'estado' => $this->estado,
        ]);

        session()->flash('success', 'Mesa creada correctamente.');

        return redirect()->route('admin.mesas.index');
    }

    public function render()
    {
        return view('livewire.admin.mesas.create');
    }
}
