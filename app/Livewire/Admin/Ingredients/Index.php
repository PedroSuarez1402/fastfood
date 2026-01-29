<?php

namespace App\Livewire\Admin\Ingredients;

use App\Models\Ingredient;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    
    // Modal
    public $showModal = false;
    public $isEditing = false;

    // Datos del Ingrediente
    public $ingredientId;
    public $name, $code, $unit, $cost, $stock, $min_stock;
    public $compra_cantidad;
    public $compra_precio_total;

    public function rules()
    {
        return [
            'name'      => 'required|string|min:3',
            'code'      => 'required|string|unique:ingredients,code,' . $this->ingredientId,
            'unit'      => 'required|in:kg,g,lb,lt,ml,unid',
            'cost'      => 'required|numeric|min:0',
            'stock'     => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
        ];
    }
    public function updatedCompraPrecioTotal()
    {
        $this->calcularCostoUnitario();
    }
    public function updatedCompraCantidad()
    {
        $this->calcularCostoUnitario();
    }
    public function calcularCostoUnitario()
    {
        // Solo calculamos si ambos valores son válidos y mayores a 0
        if (is_numeric($this->compra_cantidad) && $this->compra_cantidad > 0 && 
            is_numeric($this->compra_precio_total) && $this->compra_precio_total > 0) {
            
            // Costo Unitario = Total / Cantidad
            $this->cost = round($this->compra_precio_total / $this->compra_cantidad, 2);
            
            // Opcional: Si es un registro nuevo, asignamos el stock inicial igual a la compra
            if (!$this->isEditing) {
                $this->stock = $this->compra_cantidad;
            }
        }
    }

    public function create()
    {
        $this->reset(['name', 'code', 'unit', 'cost', 'stock', 'min_stock', 'ingredientId', 'compra_cantidad', 'compra_precio_total']);
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit(Ingredient $ingredient)
    {
        $this->ingredientId = $ingredient->id;
        $this->name      = $ingredient->name;
        $this->code      = $ingredient->code;
        $this->unit      = $ingredient->unit;
        $this->cost      = $ingredient->cost;
        $this->stock     = $ingredient->stock;
        $this->min_stock = $ingredient->min_stock;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name'      => $this->name,
            'code'      => $this->code,
            'unit'      => $this->unit,
            'cost'      => $this->cost,
            'stock'     => $this->stock,
            'min_stock' => $this->min_stock,
        ];

        if ($this->isEditing) {
            $ingredient = Ingredient::find($this->ingredientId);
            $ingredient->update($data);
        } else {
            Ingredient::create($data);
        }

        $this->showModal = false;
        session()->flash('success', 'Ingrediente guardado correctamente.');
    }

    public function delete($id)
    {
        Ingredient::find($id)->delete();
        session()->flash('success', 'Ingrediente eliminado.');
    }

    public function render()
    {
        $ingredients = Ingredient::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('code', 'like', '%' . $this->search . '%')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.ingredients.index', [
            'ingredients' => $ingredients
        ]);
    }
}