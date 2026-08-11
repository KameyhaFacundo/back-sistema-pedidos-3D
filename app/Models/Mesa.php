<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    protected $fillable = ['numero', 'activa'];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    public function llamados()
    {
        return $this->hasMany(Llamado::class);
    }
}
