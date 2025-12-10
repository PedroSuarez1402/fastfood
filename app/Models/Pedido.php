<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = ['mesa_id', 'codigo_ticket', 'nombre_cliente', 'telefono_cliente', 'estado', 'total', 'fecha_pedido'];

    protected static function booted()
    {
        // Asignar código automático
        static::creating(function ($pedido) {
            $last = static::max('id') + 1;
            $pedido->codigo_ticket = 'PED-' . str_pad($last, 4, '0', STR_PAD_LEFT);

            // asignar fecha si no viene definida
            if (empty($pedido->fecha_pedido)) {
                $pedido->fecha_pedido = now()->toDateString(); // Guarda explícitamente YYYY-MM-DD
            }
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
