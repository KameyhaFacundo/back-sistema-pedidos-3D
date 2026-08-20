<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PlatoController extends Controller
{
    /**
     * PHP's fileinfo/magic database detects .glb and .usdz as inconsistent
     * MIME types across environments, so Laravel's mime-based `mimes:` rule
     * unreliably rejects valid files. Validate by extension instead.
     */
    private static function extensionRule(string $extension)
    {
        return function ($attribute, $value, $fail) use ($extension) {
            if ($value && strtolower($value->getClientOriginalExtension()) !== $extension) {
                $fail("El campo {$attribute} debe ser un archivo .{$extension}.");
            }
        };
    }

    /**
     * store() names files via guessExtension(), which is MIME-detection based
     * and produces wrong extensions (e.g. .bin) for glb/usdz uploads. Keep
     * the original client extension instead.
     */
    private static function storeWithOriginalExtension($file, string $directory): string
    {
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();

        return $file->storeAs($directory, $filename, 'public');
    }

    public function index()
    {
        $empresa = auth()->user()?->empresa;

        if (!$empresa) {
            return response()->json(['message' => 'Empresa no encontrada'], 404);
        }

        $platos = Plato::with(['presentaciones', 'agregados'])
            ->where('empresa_id', $empresa->id)
            ->orderBy('orden')
            ->orderBy('nombre')
            ->get();

        return response()->json($platos);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'categoria' => ['nullable', Rule::in(['principales', 'entradas', 'postres', 'bebidas'])],
            'descripcion' => 'nullable|string',
            'ingredientes' => 'nullable|array',
            'ingredientes.*' => 'string|max:60',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'modelo_glb' => ['nullable', 'file', 'max:10240', self::extensionRule('glb')],
            'modelo_usdz' => ['nullable', 'file', 'max:10240', self::extensionRule('usdz')],
            'disponible' => 'boolean',
            'presentaciones' => 'nullable|array',
            'presentaciones.*.nombre' => 'required|string|max:255',
            'presentaciones.*.descripcion' => 'nullable|string',
            'presentaciones.*.precio' => 'required|numeric|min:0',
            'agregados' => 'nullable|array',
            'agregados.*.nombre' => 'required|string|max:255',
            'agregados.*.descripcion' => 'nullable|string',
            'agregados.*.precio' => 'required|numeric|min:0',
        ]);

        $empresa = auth()->user()?->empresa;

        if (!$empresa) {
            return response()->json(['message' => 'Empresa no encontrada'], 404);
        }

        $data = collect($validated)->except(['foto', 'modelo_glb', 'modelo_usdz', 'presentaciones', 'agregados'])->toArray();
        $data['empresa_id'] = $empresa->id;
        $data['orden'] = (int) Plato::where('empresa_id', $empresa->id)->max('orden') + 1;

        if ($request->hasFile('foto')) {
            $data['foto'] = asset('storage/' . $request->file('foto')->store('fotos', 'public'));
        }

        if ($request->hasFile('modelo_glb')) {
            $data['modelo_glb'] = asset('storage/' . self::storeWithOriginalExtension($request->file('modelo_glb'), 'modelos'));
        }

        if ($request->hasFile('modelo_usdz')) {
            $data['modelo_usdz'] = asset('storage/' . self::storeWithOriginalExtension($request->file('modelo_usdz'), 'modelos'));
        }

        $plato = Plato::create($data);

        $this->syncCustomization($plato, $validated);

        return response()->json($plato->load('presentaciones', 'agregados'), 201);
    }

    private function authorizePlato(Plato $plato): bool
    {
        $empresa = auth()->user()?->empresa;

        return $empresa && $plato->empresa_id === $empresa->id;
    }

    public function show(Plato $plato)
    {
        if (!$this->authorizePlato($plato)) {
            return response()->json(['message' => 'Plato no encontrado'], 404);
        }

        return response()->json($plato->load('presentaciones', 'agregados'));
    }

    public function update(Request $request, Plato $plato)
    {
        if (!$this->authorizePlato($plato)) {
            return response()->json(['message' => 'Plato no encontrado'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'precio' => 'sometimes|numeric|min:0',
            'categoria' => ['nullable', Rule::in(['principales', 'entradas', 'postres', 'bebidas'])],
            'descripcion' => 'nullable|string',
            'ingredientes' => 'nullable|array',
            'ingredientes.*' => 'string|max:60',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'modelo_glb' => ['nullable', 'file', 'max:10240', self::extensionRule('glb')],
            'modelo_usdz' => ['nullable', 'file', 'max:10240', self::extensionRule('usdz')],
            'disponible' => 'boolean',
            'presentaciones' => 'nullable|array',
            'presentaciones.*.nombre' => 'required|string|max:255',
            'presentaciones.*.descripcion' => 'nullable|string',
            'presentaciones.*.precio' => 'required|numeric|min:0',
            'agregados' => 'nullable|array',
            'agregados.*.nombre' => 'required|string|max:255',
            'agregados.*.descripcion' => 'nullable|string',
            'agregados.*.precio' => 'required|numeric|min:0',
        ]);

        $data = collect($validated)->except(['foto', 'modelo_glb', 'modelo_usdz', 'presentaciones', 'agregados'])->toArray();

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
            $data['modelo_glb'] = asset('storage/' . self::storeWithOriginalExtension($request->file('modelo_glb'), 'modelos'));
        }

        if ($request->hasFile('modelo_usdz')) {
            if ($plato->modelo_usdz) {
                $path = str_replace(asset('storage/'), '', $plato->modelo_usdz);
                Storage::disk('public')->delete($path);
            }
            $data['modelo_usdz'] = asset('storage/' . self::storeWithOriginalExtension($request->file('modelo_usdz'), 'modelos'));
        }

        $plato->update($data);

        if (array_key_exists('presentaciones', $validated) || array_key_exists('agregados', $validated)) {
            $this->syncCustomization($plato, $validated);
        }

        return response()->json($plato->load('presentaciones', 'agregados'));
    }

    private function syncCustomization(Plato $plato, array $validated): void
    {
        if (array_key_exists('presentaciones', $validated)) {
            $plato->presentaciones()->delete();
            foreach ($validated['presentaciones'] ?? [] as $i => $p) {
                $plato->presentaciones()->create([
                    'nombre' => $p['nombre'],
                    'descripcion' => $p['descripcion'] ?? null,
                    'precio' => $p['precio'],
                    'orden' => $i,
                ]);
            }
        }

        if (array_key_exists('agregados', $validated)) {
            $plato->agregados()->delete();
            foreach ($validated['agregados'] ?? [] as $i => $a) {
                $plato->agregados()->create([
                    'nombre' => $a['nombre'],
                    'descripcion' => $a['descripcion'] ?? null,
                    'precio' => $a['precio'],
                    'orden' => $i,
                ]);
            }
        }
    }

    public function reordenar(Request $request)
    {
        $empresa = auth()->user()?->empresa;

        if (!$empresa) {
            return response()->json(['message' => 'Empresa no encontrada'], 404);
        }

        $validated = $request->validate([
            'platos' => 'required|array|min:1',
            'platos.*' => 'required|integer',
        ]);

        $ids = array_values(array_unique($validated['platos']));

        $existen = Plato::where('empresa_id', $empresa->id)
            ->whereIn('id', $ids)
            ->count();

        if ($existen !== count($ids)) {
            return response()->json(['message' => 'Uno o más platos no existen para esta empresa.'], 422);
        }

        foreach ($ids as $i => $id) {
            Plato::where('empresa_id', $empresa->id)->where('id', $id)->update(['orden' => $i]);
        }

        return response()->json(['message' => 'Orden actualizado']);
    }

    public function destroy(Plato $plato)
    {
        if (!$this->authorizePlato($plato)) {
            return response()->json(['message' => 'Plato no encontrado'], 404);
        }

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
        if (!$this->authorizePlato($plato)) {
            return response()->json(['message' => 'Plato no encontrado'], 404);
        }

        $plato->update(['disponible' => !$plato->disponible]);

        return response()->json($plato);
    }
}
