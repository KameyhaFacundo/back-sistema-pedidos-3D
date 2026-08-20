<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpresaController extends Controller
{
    public function show(Request $request)
    {
        $empresa = Empresa::resolveFromRequest($request);

        if (!$empresa) {
            return response()->json(['message' => 'Empresa no encontrada'], 404);
        }

        $empresa->abierto = $empresa->estaAbiertaAhora();
        $empresa->mp_enabled = (bool) config('services.mercadopago.access_token');

        return response()->json($empresa);
    }

    public function update(Request $request)
    {
        $empresa = auth()->user()?->empresa;

        if (!$empresa) {
            return response()->json(['message' => 'Empresa no encontrada'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:120',
            'whatsapp' => 'sometimes|nullable|string|max:40',
            'logo' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'abierto' => 'sometimes|boolean',
            'horarios' => 'sometimes|nullable|array',
            'horarios.dom' => 'nullable|string|max:200',
            'horarios.lun' => 'nullable|string|max:200',
            'horarios.mar' => 'nullable|string|max:200',
            'horarios.mie' => 'nullable|string|max:200',
            'horarios.jue' => 'nullable|string|max:200',
            'horarios.vie' => 'nullable|string|max:200',
            'horarios.sab' => 'nullable|string|max:200',
            'tiempo_estimado' => 'sometimes|nullable|integer|min:0|max:600',
        ]);

        $data = collect($validated)->except('logo')->toArray();

        if ($request->hasFile('logo')) {
            if ($empresa->logo) {
                $oldPath = str_replace(asset('storage/'), '', $empresa->logo);
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $data['logo'] = asset('storage/' . $request->file('logo')->store('logos', 'public'));
        } elseif (array_key_exists('logo', $validated) && $validated['logo'] === null) {
            if ($empresa->logo) {
                $oldPath = str_replace(asset('storage/'), '', $empresa->logo);
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $data['logo'] = null;
        }

        $empresa->update($data);

        $empresa->abierto = $empresa->estaAbiertaAhora();
        $empresa->mp_enabled = (bool) config('services.mercadopago.access_token');

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