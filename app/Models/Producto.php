<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = ['categoria_id', 'nombre', 'descripcion', 'precio', 'imagen', 'disponible'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }
}
