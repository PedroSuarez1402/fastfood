<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadisticas de mesas
        $mesasOcupadas = Mesa::where('estado', 'ocupada')->count();
        $mesasDisponibles = Mesa::where('estado', 'disponible')->count();

        //Total ventas hoy
        $ventasHoy = Pedido::whereDate('created_at', Carbon::today())
            ->where('estado', 'pagado')
            ->sum('total');
        
        // Pedidos Activos
        $pedidosActivos = Pedido::whereIn('estado', ['pendiente', 'en preparación', 'servido'])->count();

        // Producto mas vendido
        $productoMasVendido = DetallePedido::selectRaw('producto_id, SUM(cantidad) as total')
            ->groupBy('producto_id')
            ->orderByDesc('total')
            ->first();

        $productoTop = null;
        if ($productoMasVendido) {
            $productoTop = Producto::find($productoMasVendido->producto_id);
        }

        // Pedidos recientes
        $pedidosRecientes = Pedido::latest()->take(8)->get();

        return view('dashboard', compact(
            'mesasOcupadas',
            'mesasDisponibles',
            'ventasHoy',
            'pedidosActivos',
            'productoTop',
            'productoMasVendido',
            'pedidosRecientes'
        ));
    }
}
