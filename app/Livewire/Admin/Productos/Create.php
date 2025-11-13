<?php

namespace App\Livewire\Admin\Productos;

use App\Models\Categoria;
use App\Models\Producto;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $categorias;
    public $categoria_id;
    public $nombre;
    public $descripcion;
    public $precio;
    public $imagen;
    public $disponible = true;

    public function mount()
    {
        $this->categorias = Categoria::orderBy('nombre')->get();
    }
    protected $rules = [
        'categoria_id' => 'required|exists:categorias,id',
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string|max:1000',
        'precio' => 'required|numeric|min:0',
        'imagen' => 'nullable|image|max:2048',
        'disponible' => 'boolean',
    ];
    public function save()
    {
        $this->validate();

        $path = $this->imagen ? $this->imagen->store('productos', 'public')
        : null;

        Producto::create([
            'categoria_id' => $this->categoria_id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
            'imagen' => $path,
            'disponible' => $this->disponible
        ]);

        session()->flash('success', 'Producto creado exitosamente.');

        return redirect()->route('admin.productos.index');
    }
    public function render()
    {
        return view('livewire.admin.productos.create');
    }
}
