<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Plato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PedidoController extends Controller
{
    public function index(Request $request)
    {
        $query = Pedido::with(['items.plato', 'mesa'])
            ->select('id', 'tipo', 'mesa_id', 'estado', 'medio_pago', 'estado_pago', 'created_at', 'updated_at');

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        $perPage = min((int) $request->get('per_page', 50), 100);
        $pedidos = $query->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json($pedidos);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo' => ['required', Rule::in(['mesa', 'retiro'])],
            'mesa_id' => 'nullable|exists:mesas,id',
            'nombre' => 'nullable|string|max:100',
            'celular' => 'nullable|string|max:30',
            'medio_pago' => ['required', Rule::in(['efectivo', 'transferencia'])],
            'items' => 'required|array|min:1',
            'items.*.plato_id' => 'required|integer',
            'items.*.cantidad' => 'required|integer|min:1',
        ]);

        if ($validated['tipo'] === 'mesa' && empty($validated['mesa_id'])) {
            return response()->json(['message' => 'El pedido de mesa requiere un número de mesa.'], 422);
        }

        $platoIds = array_column($validated['items'], 'plato_id');
        $platos = Plato::whereIn('id', $platoIds)->get()->keyBy('id');

        if ($platos->count() !== count(array_unique($platoIds))) {
            return response()->json(['message' => 'Uno o más platos no existen.'], 422);
        }

        $pedido = DB::transaction(function () use ($validated, $platos) {
            $pedido = Pedido::create([
                'tipo' => $validated['tipo'],
                'mesa_id' => $validated['mesa_id'] ?? null,
                'nombre' => $validated['nombre'] ?? null,
                'celular' => $validated['celular'] ?? null,
                'medio_pago' => $validated['medio_pago'],
                'estado' => 'nuevo',
                'estado_pago' => 'pendiente',
            ]);

            $itemsData = [];
            foreach ($validated['items'] as $item) {
                $plato = $platos[$item['plato_id']];
                $itemsData[] = [
                    'pedido_id' => $pedido->id,
                    'plato_id' => $plato->id,
                    'cantidad' => $item['cantidad'],
                    'subtotal' => $plato->precio * $item['cantidad'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            PedidoItem::insert($itemsData);

            return $pedido->load('items.plato', 'mesa');
        });

        return response()->json($pedido, 201);
    }

    public function updateEstado(Request $request, Pedido $pedido)
    {
        $validated = $request->validate([
            'estado' => ['required', Rule::in(['preparacion', 'listo', 'entregado', 'cancelado'])],
        ]);

        $transiciones = [
            'nuevo' => ['preparacion', 'cancelado'],
            'preparacion' => ['listo', 'cancelado'],
            'listo' => ['entregado'],
        ];

        $estadoActual = $pedido->estado;
        if (!isset($transiciones[$estadoActual]) || !in_array($validated['estado'], $transiciones[$estadoActual])) {
            return response()->json([
                'message' => "No se puede cambiar de '$estadoActual' a '{$validated['estado']}'.",
            ], 422);
        }

        $pedido->update(['estado' => $validated['estado']]);

        return response()->json($pedido->load('items.plato', 'mesa'));
    }

    public function cancelar(Pedido $pedido)
    {
        if (!in_array($pedido->estado, ['nuevo', 'preparacion'])) {
            return response()->json([
                'message' => 'Solo se pueden cancelar pedidos en estado nuevo o en preparación.',
            ], 422);
        }

        $pedido->update(['estado' => 'cancelado']);

        return response()->json($pedido->load('items.plato', 'mesa'));
    }

    public function show(Pedido $pedido)
    {
        return response()->json($pedido->load('items.plato', 'mesa'));
    }

    public function updatePago(Pedido $pedido)
    {
        $pedido->update(['estado_pago' => 'pagado']);

        return response()->json($pedido->load('items.plato', 'mesa'));
    }
}
