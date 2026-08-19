<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cupon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CuponController extends Controller
{
    private function empresa()
    {
        $empresa = auth()->user()?->empresa;

        if (!$empresa) {
            abort(404, 'Empresa no encontrada');
        }

        return $empresa;
    }

    public function index()
    {
        return response()->json(Cupon::where('empresa_id', $this->empresa()->id)->orderBy('codigo')->get());
    }

    public function store(Request $request)
    {
        $empresa = $this->empresa();

        $data = $request->all();
        if (isset($data['codigo'])) {
            $data['codigo'] = strtoupper(trim($data['codigo']));
        }

        $validated = validator($data, [
            'codigo' => ['required', 'string', 'max:50', Rule::unique('cupones', 'codigo')],
            'descuento' => 'required|numeric|min:0|max:10000',
            'tipo' => ['required', Rule::in(['fijo', 'porcentaje'])],
            'activo' => 'sometimes|boolean',
        ])->validate();

        $cupon = Cupon::create([
            'empresa_id' => $empresa->id,
            'codigo' => $validated['codigo'],
            'descuento' => $validated['descuento'],
            'tipo' => $validated['tipo'],
            'activo' => $validated['activo'] ?? true,
        ]);

        return response()->json($cupon, 201);
    }

    public function update(Request $request, Cupon $cupon)
    {
        $empresa = $this->empresa();

        if ($cupon->empresa_id !== $empresa->id) {
            return response()->json(['message' => 'Cupón no encontrado'], 404);
        }

        $data = $request->all();
        if (isset($data['codigo'])) {
            $data['codigo'] = strtoupper(trim($data['codigo']));
        }

        $validated = validator($data, [
            'codigo' => ['sometimes', 'string', 'max:50', Rule::unique('cupones', 'codigo')->ignore($cupon->id)],
            'descuento' => 'sometimes|numeric|min:0|max:10000',
            'tipo' => ['sometimes', Rule::in(['fijo', 'porcentaje'])],
            'activo' => 'sometimes|boolean',
        ])->validate();

        $cupon->update($validated);

        return response()->json($cupon);
    }

    public function toggleActivo(Cupon $cupon)
    {
        $empresa = $this->empresa();

        if ($cupon->empresa_id !== $empresa->id) {
            return response()->json(['message' => 'Cupón no encontrado'], 404);
        }

        $cupon->update(['activo' => !$cupon->activo]);

        return response()->json($cupon);
    }

    public function destroy(Cupon $cupon)
    {
        $empresa = $this->empresa();

        if ($cupon->empresa_id !== $empresa->id) {
            return response()->json(['message' => 'Cupón no encontrado'], 404);
        }

        $cupon->delete();

        return response()->json(['message' => 'Cupón eliminado']);
    }
}