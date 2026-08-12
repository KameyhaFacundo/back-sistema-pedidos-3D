<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Mesa;
use App\Models\Llamado;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MesaController extends Controller
{
    public function index(Request $request)
    {
        $empresa = Empresa::resolveFromRequest($request);

        $query = Mesa::query();

        if (!$request->boolean('all')) {
            $query->where('activa', true);
        }

        if ($empresa) {
            $query->where(function ($q) use ($empresa) {
                $q->where('empresa_id', $empresa->id)->orWhereNull('empresa_id');
            });
        }

        $mesas = $query->orderBy('numero')->get();

        $ocupadasPedido = Pedido::select('mesa_id')
            ->whereIn('estado', ['nuevo', 'preparacion', 'listo'])
            ->where('tipo', 'mesa')
            ->whereNotNull('mesa_id')
            ->when($empresa, function ($q) use ($empresa) {
                $q->where(fn ($sub) => $sub->where('empresa_id', $empresa->id)->orWhereNull('empresa_id'));
            })
            ->pluck('mesa_id');

        $ocupadas = $ocupadasPedido->flip();

        $data = $mesas->map(function ($mesa) use ($ocupadas) {
            return array_merge($mesa->toArray(), ['ocupada' => isset($ocupadas[$mesa->id])]);
        })->values();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $empresa = $this->resolveAdminEmpresa($request);

        $validated = $request->validate([
            'numero' => ['required', 'integer', 'min:1', Rule::unique('mesas', 'numero')->where(function ($q) use ($empresa) {
                $q->whereNull('empresa_id')->orWhere('empresa_id', $empresa?->id);
            })],
            'forma' => 'sometimes|in:circular,rectangular',
            'pos_x' => 'sometimes|nullable|numeric|min:0|max:100',
            'pos_y' => 'sometimes|nullable|numeric|min:0|max:100',
            'rotacion' => 'sometimes|integer|min:0|max:360',
            'activa' => 'sometimes|boolean',
        ]);

        $mesa = Mesa::create([
            'empresa_id' => $empresa?->id,
            'numero' => $validated['numero'],
            'forma' => $validated['forma'] ?? 'circular',
            'pos_x' => $validated['pos_x'] ?? null,
            'pos_y' => $validated['pos_y'] ?? null,
            'rotacion' => $validated['rotacion'] ?? 0,
            'activa' => $validated['activa'] ?? true,
        ]);

        return response()->json($mesa, 201);
    }

    public function update(Request $request, Mesa $mesa)
    {
        $empresa = $this->resolveAdminEmpresa($request);

        if ($empresa && $mesa->empresa_id && $mesa->empresa_id !== $empresa->id) {
            return response()->json(['message' => 'No tenés acceso a esta mesa'], 403);
        }

        $validated = $request->validate([
            'numero' => 'sometimes|integer|min:1',
            'forma' => 'sometimes|in:circular,rectangular',
            'pos_x' => 'sometimes|nullable|numeric|min:0|max:100',
            'pos_y' => 'sometimes|nullable|numeric|min:0|max:100',
            'rotacion' => 'sometimes|integer|min:0|max:360',
            'activa' => 'sometimes|boolean',
        ]);

        $mesa->update($validated);

        return response()->json($mesa);
    }

    public function destroy(Request $request, Mesa $mesa)
    {
        $empresa = $this->resolveAdminEmpresa($request);

        if ($empresa && $mesa->empresa_id && $mesa->empresa_id !== $empresa->id) {
            return response()->json(['message' => 'No tenés acceso a esta mesa'], 403);
        }

        $mesa->delete();

        return response()->json(['message' => 'Mesa eliminada']);
    }

    public function toggleActiva(Request $request, Mesa $mesa)
    {
        $empresa = $this->resolveAdminEmpresa($request);

        if ($empresa && $mesa->empresa_id && $mesa->empresa_id !== $empresa->id) {
            return response()->json(['message' => 'No tenés acceso a esta mesa'], 403);
        }

        $mesa->update(['activa' => !$mesa->activa]);

        return response()->json($mesa);
    }

    public function llamar(Mesa $mesa)
    {
        Llamado::create([
            'empresa_id' => $mesa->empresa_id,
            'mesa_id' => $mesa->id,
            'atendido' => false,
        ]);

        return response()->json(['message' => 'Mozo llamado a la mesa ' . $mesa->numero]);
    }

    private function resolveAdminEmpresa(Request $request): ?Empresa
    {
        return Empresa::resolveFromRequest($request);
    }
}