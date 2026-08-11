<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Llamado;

class CocinaController extends Controller
{
    public function llamados()
    {
        $llamados = Llamado::with('mesa')
            ->where('atendido', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($llamados);
    }

    public function atenderLlamado(Llamado $llamado)
    {
        $llamado->update(['atendido' => true]);

        return response()->json(['message' => 'Llamado atendido']);
    }
}
