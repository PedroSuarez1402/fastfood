<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = ['mesa_id', 'codigo_ticket', 'nombre_cliente', 'telefono_cliente', 'estado', 'total'];

    protected static function booted()
    {
        static::creating(function ($pedido) {
            $last = static::max('id') + 1;
            $pedido->codigo_ticket = 'PED-' . str_pad($last, 4, '0', STR_PAD_LEFT);
        });
    }
    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }
}
