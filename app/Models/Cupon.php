<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cupon extends Model
{
    protected $table = 'cupones';

    protected $fillable = ['empresa_id', 'codigo', 'descuento', 'tipo', 'activo'];

    protected $casts = [
        'descuento' => 'float',
        'activo' => 'boolean',
    ];
}
