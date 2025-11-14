<?php

namespace App\Livewire\Public;

use App\Models\Producto;
use Livewire\Component;

class Products extends Component
{
    public $search = '';
    public function render()
    {
        $productos = Producto::where('disponible', true)
            ->where('nombre', 'like', '%' . $this->search . '%')
            ->get();
        return view('livewire.public.products', [
            'productos' => $productos
        ]);
    }
}
