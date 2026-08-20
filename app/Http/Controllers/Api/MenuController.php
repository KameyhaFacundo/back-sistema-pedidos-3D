<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Plato;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $empresa = Empresa::resolveFromRequest($request);

        // Without a resolved empresa there's no tenant to scope by -- return
        // empty instead of every company's menu merged together.
        if (!$empresa) {
            return response()->json(['message' => 'Empresa no encontrada'], 404);
        }

        $empresa->abierto = $empresa->estaAbiertaAhora();
        $empresa->mp_enabled = (bool) config('services.mercadopago.access_token');

        $platos = Plato::with(['presentaciones', 'agregados'])
            ->where('disponible', true)
            ->where('empresa_id', $empresa->id)
            ->orderBy('orden')
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'empresa' => $empresa,
            'platos' => $platos,
        ]);
    }
}
