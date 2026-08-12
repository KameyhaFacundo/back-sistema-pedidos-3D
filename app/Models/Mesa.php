<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    protected $fillable = ['empresa_id', 'numero', 'activa', 'pos_x', 'pos_y', 'rotacion', 'forma'];

    protected $casts = [
        'activa' => 'boolean',
        'pos_x' => 'float',
        'pos_y' => 'float',
        'rotacion' => 'integer',
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

    public static function posicionesPorDefecto(int $count): array
    {
        $posiciones = [];
        for ($i = 0; $i < $count; $i++) {
            $col = $i % 4;
            $row = intdiv($i, 4);
            $posiciones[] = ['pos_x' => (float) (16 + $col * 18), 'pos_y' => (float) (18 + $row * 20)];
        }
        return $posiciones;
    }
}
