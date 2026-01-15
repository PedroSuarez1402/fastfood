<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = ['name', 'code', 'unit', 'cost', 'stock', 'min_stock'];

    public function productos()
    {
        // Especificamos la tabla pivote y la foreign key correcta por si acaso
        return $this->belongsToMany(Producto::class, 'product_ingredient', 'ingredient_id', 'producto_id')
                    ->withPivot('quantity');
    }
}
