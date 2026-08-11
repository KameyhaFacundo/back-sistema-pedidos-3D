<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plato extends Model
{
    protected $fillable = [
        'nombre', 'categoria', 'descripcion', 'precio', 'foto', 'modelo_glb', 'modelo_usdz', 'disponible',
    ];

    protected $casts = [
        'precio' => 'float',
        'disponible' => 'boolean',
    ];
}
