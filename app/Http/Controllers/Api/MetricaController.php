<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ArVista;
use App\Models\Empresa;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Plato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MetricaController extends Controller
{
    public function index()
    {
        $empresa = auth()->user()?->empresa;

        if (!$empresa) {
            return response()->json(['message' => 'Empresa no encontrada'], 404);
        }

        $hoy = now()->format('Y-m-d');

        $totalPedidos = Pedido::where('empresa_id', $empresa->id)
            ->whereDate('created_at', $hoy)
            ->count();

        $ventasHoy = PedidoItem::whereHas('pedido', function ($q) use ($hoy, $empresa) {
            $q->where('empresa_id', $empresa->id)
                ->whereDate('created_at', $hoy);
        })->sum('subtotal');

        $activos = Pedido::where('empresa_id', $empresa->id)
            ->whereIn('estado', ['nuevo', 'preparacion', 'listo'])
            ->count();

        $masPedido = PedidoItem::select('plato_id', DB::raw('SUM(cantidad) as total'))
            ->whereHas('pedido', function ($q) use ($hoy, $empresa) {
                $q->where('empresa_id', $empresa->id)
                    ->whereDate('created_at', $hoy);
            })
            ->groupBy('plato_id')
            ->orderByDesc('total')
            ->first();

        $masPedidoNombre = null;
        if ($masPedido) {
            $plato = Plato::where('empresa_id', $empresa->id)->find($masPedido->plato_id);
            $masPedidoNombre = $plato?->nombre;
        }

        $arVistas = ArVista::select('plato_id', DB::raw('COUNT(*) as total'))
            ->where('empresa_id', $empresa->id)
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

    public function registrarVista(Request $request, Plato $plato)
    {
        $empresa = Empresa::resolveFromRequest($request);

        if (!$empresa || $plato->empresa_id !== $empresa->id) {
            return response()->json(['message' => 'Plato no encontrado'], 404);
        }

        ArVista::create([
            'empresa_id' => $empresa->id,
            'plato_id' => $plato->id,
        ]);

        return response()->json(['message' => 'Vista AR registrada']);
    }
}
