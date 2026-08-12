<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Mesa;
use App\Models\Llamado;
use Illuminate\Http\Request;

class MesaController extends Controller
{
    public function index(Request $request)
    {
        $empresa = Empresa::resolveFromRequest($request);

        $query = Mesa::where('activa', true);

        if ($empresa) {
            $query->where('empresa_id', $empresa->id);
        }

        $mesas = $query->orderBy('numero')->get();

        return response()->json($mesas);
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
}
