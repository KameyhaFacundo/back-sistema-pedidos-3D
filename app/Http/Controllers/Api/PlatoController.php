<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PlatoController extends Controller
{
    public function index()
    {
        $platos = Plato::orderBy('nombre')->get();

        return response()->json($platos);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'categoria' => ['nullable', Rule::in(['principales', 'entradas', 'postres', 'bebidas'])],
            'descripcion' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'modelo_glb' => 'nullable|file|mimes:glb|max:10240',
            'modelo_usdz' => 'nullable|file|mimes:usdz|max:10240',
            'disponible' => 'boolean',
        ]);

        $data = collect($validated)->except(['foto', 'modelo_glb', 'modelo_usdz'])->toArray();

        if ($request->hasFile('foto')) {
            $data['foto'] = asset('storage/' . $request->file('foto')->store('fotos', 'public'));
        }

        if ($request->hasFile('modelo_glb')) {
            $data['modelo_glb'] = asset('storage/' . $request->file('modelo_glb')->store('modelos', 'public'));
        }

        if ($request->hasFile('modelo_usdz')) {
            $data['modelo_usdz'] = asset('storage/' . $request->file('modelo_usdz')->store('modelos', 'public'));
        }

        $plato = Plato::create($data);

        return response()->json($plato, 201);
    }

    public function show(Plato $plato)
    {
        return response()->json($plato);
    }

    public function update(Request $request, Plato $plato)
    {
        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'precio' => 'sometimes|numeric|min:0',
            'categoria' => ['nullable', Rule::in(['principales', 'entradas', 'postres', 'bebidas'])],
            'descripcion' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'modelo_glb' => 'nullable|file|mimes:glb|max:10240',
            'modelo_usdz' => 'nullable|file|mimes:usdz|max:10240',
            'disponible' => 'boolean',
        ]);

        $data = collect($validated)->except(['foto', 'modelo_glb', 'modelo_usdz'])->toArray();

        if ($request->hasFile('foto')) {
            if ($plato->foto) {
                $path = str_replace(asset('storage/'), '', $plato->foto);
                Storage::disk('public')->delete($path);
            }
            $data['foto'] = asset('storage/' . $request->file('foto')->store('fotos', 'public'));
        }

        if ($request->hasFile('modelo_glb')) {
            if ($plato->modelo_glb) {
                $path = str_replace(asset('storage/'), '', $plato->modelo_glb);
                Storage::disk('public')->delete($path);
            }
            $data['modelo_glb'] = asset('storage/' . $request->file('modelo_glb')->store('modelos', 'public'));
        }

        if ($request->hasFile('modelo_usdz')) {
            if ($plato->modelo_usdz) {
                $path = str_replace(asset('storage/'), '', $plato->modelo_usdz);
                Storage::disk('public')->delete($path);
            }
            $data['modelo_usdz'] = asset('storage/' . $request->file('modelo_usdz')->store('modelos', 'public'));
        }

        $plato->update($data);

        return response()->json($plato);
    }

    public function destroy(Plato $plato)
    {
        foreach (['foto', 'modelo_glb', 'modelo_usdz'] as $field) {
            if ($plato->$field) {
                $path = str_replace(asset('storage/'), '', $plato->$field);
                Storage::disk('public')->delete($path);
            }
        }

        $plato->delete();

        return response()->json(['message' => 'Plato eliminado']);
    }

    public function toggleDisponible(Plato $plato)
    {
        $plato->update(['disponible' => !$plato->disponible]);

        return response()->json($plato);
    }
}
