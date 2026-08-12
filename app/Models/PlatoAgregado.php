<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatoAgregado extends Model
{
    protected $fillable = ['plato_id', 'nombre', 'descripcion', 'precio', 'orden'];

    protected $casts = [
        'precio' => 'float',
        'orden' => 'integer',
    ];

    public function plato()
    {
        return $this->belongsTo(Plato::class);
    }
}
