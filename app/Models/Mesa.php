<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    protected $fillable = ['empresa_id', 'numero', 'activa'];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    public function llamados()
    {
        return $this->hasMany(Llamado::class);
    }
}
