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
        // 1. Estadísticas de Mesas
        $mesasOcupadas = Mesa::where('estado', 'ocupada')->count();
        $mesasDisponibles = Mesa::where('estado', 'disponible')->count();

        // 2. Total ventas hoy (Dinero)
        $ventasHoy = Pedido::where('fecha_pedido', Carbon::today()) // Usamos tu nueva columna
            ->where('estado', 'pagado')
            ->sum('total');
        
        // 3. Pedidos Activos
        $pedidosActivos = Pedido::whereIn('estado', ['pendiente', 'en preparación', 'servido'])->count();
        
        // AGREGADO: Pedidos Pagados Hoy (Para comparar en el gráfico)
        $pedidosPagadosHoy = Pedido::where('fecha_pedido', Carbon::today())
            ->where('estado', 'pagado')
            ->count();

        // 4. Producto más vendido (Este lo dejaremos como texto destacado o gráfico simple)
        $productoMasVendido = DetallePedido::selectRaw('producto_id, SUM(cantidad) as total')
            ->groupBy('producto_id')
            ->orderByDesc('total')
            ->first();

        $productoTop = null;
        if ($productoMasVendido) {
            $productoTop = Producto::find($productoMasVendido->producto_id);
        }

        // ... (El resto de tu código de Pedidos Recientes y Gráficos grandes sigue igual) ...
        $pedidosRecientes = Pedido::latest()->take(8)->get();

        $ventasUltimos7Dias = Pedido::selectRaw('fecha_pedido as fecha, SUM(total) as total')
            ->where('estado', 'pagado')
            ->where('fecha_pedido', '>=', now()->subDays(7)->toDateString()) 
            ->groupBy('fecha_pedido')
            ->orderBy('fecha_pedido')
            ->get();

        $labelsVentas = $ventasUltimos7Dias->pluck('fecha')->map(fn($f) => Carbon::parse($f)->format('d M'));
        $datosVentas = $ventasUltimos7Dias->pluck('total');

        $topProductos = DetallePedido::selectRaw('producto_id, SUM(cantidad) as total')
            ->groupBy('producto_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        $labelsProductos = [];
        $datosProductos = [];

        foreach ($topProductos as $item) {
            $producto = Producto::find($item->producto_id);
            if ($producto) {
                $labelsProductos[] = $producto->nombre;
                $datosProductos[] = $item->total;
            }
        }

        return view('dashboard', compact(
            'mesasOcupadas',
            'mesasDisponibles',
            'ventasHoy',
            'pedidosActivos',
            'pedidosPagadosHoy', // <--- No olvides enviar esta nueva variable
            'productoTop',
            'productoMasVendido',
            'pedidosRecientes',
            'labelsVentas',
            'datosVentas',
            'labelsProductos',
            'datosProductos'
        ));
    }
}