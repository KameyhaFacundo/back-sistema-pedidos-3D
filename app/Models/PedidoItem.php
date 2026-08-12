<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoItem extends Model
{
    protected $fillable = ['pedido_id', 'plato_id', 'cantidad', 'presentacion_nombre', 'agregados', 'observacion', 'subtotal'];

    protected $casts = [
        'subtotal' => 'float',
        'agregados' => 'array',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function plato()
    {
        return $this->belongsTo(Plato::class);
    }
}
