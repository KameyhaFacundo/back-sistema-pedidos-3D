<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cupon extends Model
{
    protected $fillable = ['codigo', 'descuento', 'tipo', 'activo'];

    protected $casts = [
        'descuento' => 'float',
        'activo' => 'boolean',
    ];
}
