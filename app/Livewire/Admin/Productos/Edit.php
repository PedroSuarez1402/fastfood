<?php

namespace App\Livewire\Admin\Productos;

use App\Models\Producto;
use App\Models\Categoria;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Edit extends Component
{
    use WithFileUploads;

    public $producto;
    public $categorias;
    public $nueva_imagen;

    public $categoria_id;
    public $nombre;
    public $descripcion;
    public $precio;
    public $imagen;
    public $disponible = false;

    public function mount(Producto $producto)
    {
        $this->producto = $producto;
        $this->categorias = Categoria::all();

        $this->categoria_id = $producto->categoria_id;
        $this->nombre = $producto->nombre;
        $this->descripcion = $producto->descripcion;
        $this->precio = $producto->precio;
        $this->imagen = $producto->imagen;
        $this->disponible = (bool) $producto->disponible;
    }
    public function update()
    {
        $this->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'nueva_imagen' => 'nullable|image|max:2048', // imagen opcional
        ]);

        // Si el usuario subió una nueva imagen
        if ($this->nueva_imagen) {

            if ($this->producto->imagen && Storage::exists('public/' . $this->producto->imagen)) {
                Storage::delete('public/' . $this->producto->imagen);
            }

            $this->producto->imagen = $this->nueva_imagen->store('productos', 'public');

            // 🟢 ACTUALIZAR LA PROPIEDAD QUE LA VISTA USA
            $this->imagen = $this->producto->imagen;
        }
        

        $this->producto->update([
            'categoria_id' => $this->categoria_id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
            'disponible' => (bool) $this->disponible,
            'imagen' => $this->producto->imagen,
        ]);

        session()->flash('success', 'Producto actualizado correctamente.');

        return redirect()->route('admin.productos.index');
    }
    public function render()
    {
        return view('livewire.admin.productos.edit', [
            'categorias' => $this->categorias,
        ]);
    }
}
