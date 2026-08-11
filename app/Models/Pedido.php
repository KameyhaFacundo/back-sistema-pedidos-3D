<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'tipo', 'mesa_id', 'estado', 'medio_pago', 'estado_pago',
    ];

    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }

    public function items()
    {
        return $this->hasMany(PedidoItem::class);
    }
}
