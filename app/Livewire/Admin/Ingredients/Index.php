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

    public function rules()
    {
        return [
            'name'      => 'required|string|min:3',
            'code'      => 'required|string|unique:ingredients,code,' . $this->ingredientId,
            'unit'      => 'required|in:kg,g,l,ml,unid',
            'cost'      => 'required|numeric|min:0',
            'stock'     => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
        ];
    }

    public function create()
    {
        $this->reset(['name', 'code', 'unit', 'cost', 'stock', 'min_stock', 'ingredientId']);
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