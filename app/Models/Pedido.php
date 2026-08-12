<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'empresa_id', 'tipo', 'mesa_id', 'nombre', 'celular', 'direccion', 'descuento', 'cupon_id', 'estado', 'medio_pago', 'estado_pago',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }

    public function items()
    {
        return $this->hasMany(PedidoItem::class);
    }

    public function cupon()
    {
        return $this->belongsTo(Cupon::class);
    }
}
