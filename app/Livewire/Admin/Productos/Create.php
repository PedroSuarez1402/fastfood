<?php

namespace App\Livewire\Admin\Productos;

use App\Models\Categoria;
use App\Models\Ingredient;
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

    public $ingredientes_disponibles;
    public $ingrediente_seleccionado;
    public $cantidad_ingrediente;
    public $receta = [];

    public function mount()
    {
        $this->categorias = Categoria::orderBy('nombre')->get();

        $this->ingredientes_disponibles = Ingredient::orderBy('name')->get();
    }
    protected $rules = [
        'categoria_id' => 'required|exists:categorias,id',
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string|max:1000',
        'precio' => 'required|numeric|min:0',
        'imagen' => 'nullable|image|max:2048',
        'disponible' => 'boolean',
        'receta' => 'array',
    ];

    public function agregarIngrediente()
    {
        // Validar solo estos campos
        $this->validate([
            'ingrediente_seleccionado' => 'required|exists:ingredients,id',
            'cantidad_ingrediente' => 'required|numeric|min:0.001',
        ]);

        // Verificar si ya existe en la receta
        foreach ($this->receta as $item) {
            if ($item['ingredient_id'] == $this->ingrediente_seleccionado) {
                $this->addError('ingrediente_seleccionado', 'Este ingrediente ya está en la receta.');
                return;
            }
        }

        // Buscar el nombre y unidad para mostrar en la tabla
        $ingredienteDB = Ingredient::find($this->ingrediente_seleccionado);

        // Agregar al array
        $this->receta[] = [
            'ingredient_id' => $ingredienteDB->id,
            'nombre' => $ingredienteDB->name,
            'unidad' => $ingredienteDB->unit,
            'cantidad' => $this->cantidad_ingrediente,
            'costo_aprox' => $ingredienteDB->cost * $this->cantidad_ingrediente // Opcional: Para ver costo
        ];

        // Limpiar inputs de ingrediente
        $this->reset(['ingrediente_seleccionado', 'cantidad_ingrediente']);
    }
    // Método para quitar ingrediente de la tabla temporal
    public function quitarIngrediente($index)
    {
        unset($this->receta[$index]);
        $this->receta = array_values($this->receta); // Reindexar array
    }
    public function save()
    {
        $this->validate();

        $path = $this->imagen ? $this->imagen->store('productos', 'public')
        : null;

        // 1. Crear Producto
        $producto = Producto::create([
            'categoria_id' => $this->categoria_id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
            'imagen' => $path,
            'disponible' => $this->disponible
        ]);

        // 2. Guardar Receta (Tabla Pivote)
        // Recorremos el array temporal y lo guardamos en la BD
        foreach ($this->receta as $item) {
            $producto->ingredientes()->attach($item['ingredient_id'], [
                'quantity' => $item['cantidad']
            ]);
        }

        session()->flash('success', 'Producto y receta creados exitosamente.');

        return redirect()->route('admin.productos.index');
    }
    public function render()
    {
        return view('livewire.admin.productos.create');
    }
}
