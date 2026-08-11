<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plato;

class MenuController extends Controller
{
    public function index()
    {
        $platos = Plato::where('disponible', true)
            ->orderBy('nombre')
            ->get();

        return response()->json($platos);
    }
}
