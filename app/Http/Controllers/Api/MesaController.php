<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mesa;
use App\Models\Llamado;

class MesaController extends Controller
{
    public function index()
    {
        $mesas = Mesa::where('activa', true)
            ->orderBy('numero')
            ->get();

        return response()->json($mesas);
    }

    public function llamar(Mesa $mesa)
    {
        Llamado::create([
            'mesa_id' => $mesa->id,
            'atendido' => false,
        ]);

        return response()->json(['message' => 'Mozo llamado a la mesa ' . $mesa->numero]);
    }
}
