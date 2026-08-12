<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plato extends Model
{
    protected $fillable = [
        'empresa_id', 'nombre', 'categoria', 'descripcion', 'precio', 'foto', 'modelo_glb', 'modelo_usdz', 'disponible',
    ];

    protected $casts = [
        'precio' => 'float',
        'disponible' => 'boolean',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function presentaciones()
    {
        return $this->hasMany(PlatoPresentacion::class)->orderBy('orden');
    }

    public function agregados()
    {
        return $this->hasMany(PlatoAgregado::class)->orderBy('orden');
    }
}
