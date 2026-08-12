<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cupon;
use App\Models\Empresa;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Plato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PedidoController extends Controller
{
    public function index(Request $request)
    {
        $empresa = auth()->user()?->empresa;

        $query = Pedido::with(['items.plato', 'mesa', 'cupon'])
            ->select('id', 'tipo', 'mesa_id', 'nombre', 'celular', 'direccion', 'descuento', 'cupon_id', 'estado', 'medio_pago', 'estado_pago', 'created_at', 'updated_at')
            ->when($empresa, fn($query) => $query->where('empresa_id', $empresa->id));

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
        $empresa = Empresa::resolveFromRequest($request);

        if (!$empresa) {
            return response()->json(['message' => 'Empresa no encontrada'], 404);
        }

        $validated = $request->validate([
            'tipo' => ['required', Rule::in(['mesa', 'retiro', 'envio'])],
            'mesa_id' => 'nullable|exists:mesas,id',
            'nombre' => 'nullable|string|max:100',
            'celular' => 'nullable|string|max:30',
            'direccion' => 'nullable|string|max:255',
            'medio_pago' => ['required', Rule::in(['efectivo', 'transferencia'])],
            'cupon_codigo' => 'nullable|string|max:50',
            'items' => 'required|array|min:1',
            'items.*.plato_id' => 'required|integer',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.presentacion_nombre' => 'nullable|string|max:255',
            'items.*.agregados' => 'nullable|array',
            'items.*.observacion' => 'nullable|string|max:150',
        ]);

        if ($validated['tipo'] === 'mesa' && empty($validated['mesa_id'])) {
            return response()->json(['message' => 'El pedido de mesa requiere un número de mesa.'], 422);
        }

        $platoIds = array_column($validated['items'], 'plato_id');
        $platos = Plato::with(['presentaciones', 'agregados'])
            ->whereIn('id', $platoIds)
            ->where('empresa_id', $empresa->id)
            ->get()
            ->keyBy('id');

        if ($platos->count() !== count(array_unique($platoIds))) {
            return response()->json(['message' => 'Uno o más platos no existen para esta empresa.'], 422);
        }

        $cupon = null;
        $descuento = 0;

        if (!empty($validated['cupon_codigo'])) {
            $cupon = Cupon::where('codigo', $validated['cupon_codigo'])
                ->where('empresa_id', $empresa->id)
                ->where('activo', true)
                ->first();
            if (!$cupon) {
                return response()->json(['message' => 'El cupón ingresado no es válido para esta empresa.'], 422);
            }
        }

        if (!empty($validated['mesa_id'])) {
            $mesa = Mesa::where('empresa_id', $empresa->id)->find($validated['mesa_id']);
            if (!$mesa) {
                return response()->json(['message' => 'Mesa no válida para esta empresa.'], 422);
            }
        }

        $pedido = DB::transaction(function () use ($validated, $platos, $cupon, &$descuento, $empresa) {
            $pedido = Pedido::create([
                'empresa_id' => $empresa->id,
                'token' => Str::random(40),
                'tipo' => $validated['tipo'],
                'mesa_id' => $validated['mesa_id'] ?? null,
                'nombre' => $validated['nombre'] ?? null,
                'celular' => $validated['celular'] ?? null,
                'direccion' => $validated['direccion'] ?? null,
                'medio_pago' => $validated['medio_pago'],
                'estado' => 'nuevo',
                'estado_pago' => 'pendiente',
                'cupon_id' => $cupon?->id,
                'descuento' => 0,
            ]);

            $itemsData = [];
            $total = 0;

            foreach ($validated['items'] as $item) {
                $plato = $platos[$item['plato_id']];

                $precioBase = (float) $plato->precio;

                if (!empty($item['presentacion_nombre'])) {
                    $presentacion = $plato->presentaciones->firstWhere('nombre', $item['presentacion_nombre']);
                    if ($presentacion) {
                        $precioBase = (float) $presentacion->precio;
                    }
                }

                $agregados = [];
                $precioAgregados = 0;
                foreach ($item['agregados'] ?? [] as $ag) {
                    $nombre = is_array($ag) ? ($ag['nombre'] ?? '') : $ag;
                    $cantidad = is_array($ag) ? ($ag['cantidad'] ?? 1) : 1;
                    $agregadoModel = $plato->agregados->firstWhere('nombre', $nombre);
                    $precioUnitario = $agregadoModel ? (float) $agregadoModel->precio : 0;
                    $precioAgregados += $precioUnitario * $cantidad;
                    $agregados[] = [
                        'nombre' => $nombre,
                        'cantidad' => $cantidad,
                        'precio' => $precioUnitario,
                    ];
                }

                $subtotal = ($precioBase + $precioAgregados) * $item['cantidad'];
                $total += $subtotal;

                $itemsData[] = [
                    'pedido_id' => $pedido->id,
                    'plato_id' => $plato->id,
                    'cantidad' => $item['cantidad'],
                    'presentacion_nombre' => $item['presentacion_nombre'] ?? null,
                    'agregados' => $agregados ? json_encode($agregados) : null,
                    'observacion' => $item['observacion'] ?? null,
                    'subtotal' => $subtotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            PedidoItem::insert($itemsData);

            if ($cupon) {
                if ($cupon->tipo === 'porcentaje') {
                    $descuento = round($total * ($cupon->descuento / 100), 2);
                } else {
                    $descuento = min((float) $cupon->descuento, $total);
                }
                $pedido->update(['descuento' => $descuento]);
            }

            return $pedido->load('items.plato', 'mesa', 'cupon');
        });

        return response()->json($pedido, 201);
    }

    public function validarCupon(Request $request)
    {
        $empresa = auth()->user()?->empresa;

        $validated = $request->validate([
            'codigo' => 'required|string|max:50',
        ]);

        $cupon = Cupon::where('codigo', $validated['codigo'])
            ->where('empresa_id', $empresa?->id)
            ->where('activo', true)
            ->first();

        if (!$cupon) {
            return response()->json(['message' => 'El cupón ingresado no es válido.'], 422);
        }

        return response()->json([
            'id' => $cupon->id,
            'codigo' => $cupon->codigo,
            'descuento' => (float) $cupon->descuento,
            'tipo' => $cupon->tipo,
        ]);
    }

    private function authorizePedido(Pedido $pedido): bool
    {
        $empresa = auth()->user()?->empresa;

        return $empresa && $pedido->empresa_id === $empresa->id;
    }

    public function updateEstado(Request $request, Pedido $pedido)
    {
        if (!$this->authorizePedido($pedido)) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }

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
        if (!$this->authorizePedido($pedido)) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }

        if (!in_array($pedido->estado, ['nuevo', 'preparacion'])) {
            return response()->json([
                'message' => 'Solo se pueden cancelar pedidos en estado nuevo o en preparación.',
            ], 422);
        }

        $pedido->update(['estado' => 'cancelado']);

        return response()->json($pedido->load('items.plato', 'mesa'));
    }

    public function show(Request $request, Pedido $pedido)
    {
        // Public route: an authenticated admin may view any pedido from
        // their own empresa; an anonymous customer (the common case, e.g.
        // polling their own order's status) must present the token they
        // were handed when the pedido was created. Pedido ids are
        // sequential, so without this token check anyone could enumerate
        // and read every other customer's (and every other company's) order.
        //
        // This route isn't behind the auth:sanctum middleware (it must stay
        // reachable with no token at all), so the bearer token is resolved
        // manually here instead of via auth()->user().
        $bearer = $request->bearerToken();
        $user = $bearer ? \Laravel\Sanctum\PersonalAccessToken::findToken($bearer)?->tokenable : null;

        if ($user) {
            $empresa = $user->empresa;
            if (!$empresa || $pedido->empresa_id !== $empresa->id) {
                return response()->json(['message' => 'Pedido no encontrado'], 404);
            }
        } else {
            $token = $request->query('token');
            if (!$token || !$pedido->token || !hash_equals($pedido->token, $token)) {
                return response()->json(['message' => 'Pedido no encontrado'], 404);
            }
        }

        return response()->json($pedido->load('items.plato', 'mesa', 'cupon'));
    }

    public function updatePago(Pedido $pedido)
    {
        if (!$this->authorizePedido($pedido)) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }

        $pedido->update(['estado_pago' => 'pagado']);

        return response()->json($pedido->load('items.plato', 'mesa'));
    }
}
