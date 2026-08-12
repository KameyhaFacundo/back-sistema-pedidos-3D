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
        // Authenticated route: always the logged-in admin's own empresa,
        // never a client-supplied slug/header (that would let an admin of
        // one company overwrite another company's floor plan).
        $empresa = auth()->user()?->empresa;

        if (!$empresa) {
            return response()->json(['message' => 'Empresa no encontrada'], 404);
        }

        $validated = $request->validate([
            'layout' => 'nullable|array',
            'layout.*.tipo' => 'required_with:layout|string|max:40',
            'layout.*.x' => 'required_with:layout|numeric|min:0|max:100',
            'layout.*.y' => 'required_with:layout|numeric|min:0|max:100',
            'layout.*.w' => 'sometimes|numeric|min:1|max:100',
            'layout.*.h' => 'sometimes|numeric|min:1|max:100',
            'layout.*.rotacion' => 'sometimes|integer|min:0|max:360',
            'layout.*.etiqueta' => 'sometimes|nullable|string|max:60',
        ]);

        $empresa->update(['layout' => $validated['layout'] ?? []]);

        return response()->json($empresa);
    }
}