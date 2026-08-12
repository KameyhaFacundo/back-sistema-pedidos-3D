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

        $query = Plato::with(['presentaciones', 'agregados'])
            ->where('disponible', true);

        if ($empresa) {
            $query->where('empresa_id', $empresa->id);
        }

        $platos = $query->orderBy('nombre')->get();

        return response()->json([
            'empresa' => $empresa,
            'platos' => $platos,
        ]);
    }
}
