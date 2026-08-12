<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Llamado;

class CocinaController extends Controller
{
    public function llamados()
    {
        $empresa = auth()->user()?->empresa;

        if (!$empresa) {
            return response()->json(['message' => 'Empresa no encontrada'], 404);
        }

        $llamados = Llamado::with('mesa')
            ->where('empresa_id', $empresa->id)
            ->where('atendido', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($llamados);
    }

    public function atenderLlamado(Llamado $llamado)
    {
        $empresa = auth()->user()?->empresa;
        if (!$empresa || $llamado->empresa_id !== $empresa->id) {
            return response()->json(['message' => 'Llamado no encontrado'], 404);
        }

        $llamado->update(['atendido' => true]);

        return response()->json(['message' => 'Llamado atendido']);
    }
}
