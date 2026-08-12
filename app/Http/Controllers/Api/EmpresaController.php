<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function show(Request $request)
    {
        $empresa = Empresa::resolveFromRequest($request);

        if (!$empresa) {
            return response()->json(['message' => 'Empresa no encontrada'], 404);
        }

        return response()->json($empresa);
    }

    public function updateLayout(Request $request)
    {
        $empresa = Empresa::resolveFromRequest($request);

        if (!$empresa) {
            return response()->json(['message' => 'Empresa no encontrada'], 404);
        }

        $validated = $request->validate([
            'layout' => 'required|array',
            'layout.*.tipo' => 'required|string|max:40',
            'layout.*.x' => 'required|numeric|min:0|max:100',
            'layout.*.y' => 'required|numeric|min:0|max:100',
            'layout.*.w' => 'sometimes|numeric|min:1|max:100',
            'layout.*.h' => 'sometimes|numeric|min:1|max:100',
            'layout.*.rotacion' => 'sometimes|integer|min:0|max:360',
            'layout.*.etiqueta' => 'sometimes|nullable|string|max:60',
        ]);

        $empresa->update(['layout' => $validated['layout']]);

        return response()->json($empresa);
    }
}