<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ArVista;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Plato;
use Illuminate\Support\Facades\DB;

class MetricaController extends Controller
{
    public function index()
    {
        $hoy = now()->format('Y-m-d');

        $totalPedidos = Pedido::whereDate('created_at', $hoy)->count();

        $ventasHoy = PedidoItem::whereHas('pedido', function ($q) use ($hoy) {
            $q->whereDate('created_at', $hoy);
        })->sum('subtotal');

        $activos = Pedido::whereIn('estado', ['nuevo', 'preparacion', 'listo'])->count();

        $masPedido = PedidoItem::select('plato_id', DB::raw('SUM(cantidad) as total'))
            ->whereHas('pedido', function ($q) use ($hoy) {
                $q->whereDate('created_at', $hoy);
            })
            ->groupBy('plato_id')
            ->orderByDesc('total')
            ->first();

        $masPedidoNombre = null;
        if ($masPedido) {
            $plato = Plato::find($masPedido->plato_id);
            $masPedidoNombre = $plato?->nombre;
        }

        $arVistas = ArVista::select('plato_id', DB::raw('COUNT(*) as total'))
            ->whereDate('created_at', '>=', now()->subDays(7))
            ->groupBy('plato_id')
            ->get()
            ->keyBy('plato_id');

        return response()->json([
            'pedidos_hoy' => $totalPedidos,
            'ventas_hoy' => number_format($ventasHoy, 2, '.', ''),
            'activos_ahora' => $activos,
            'mas_pedido' => $masPedidoNombre,
            'ar_vistas' => $arVistas->map(fn($v) => $v->total)->toArray(),
        ]);
    }

    public function registrarVista(Plato $plato)
    {
        ArVista::create(['plato_id' => $plato->id]);

        return response()->json(['message' => 'Vista AR registrada']);
    }
}
