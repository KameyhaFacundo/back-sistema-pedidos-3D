<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Empresa extends Model
{
    protected $fillable = ['slug', 'nombre', 'whatsapp', 'activo'];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public static function resolveFromRequest(Request $request): ?self
    {
        $slug = $request->header('X-Empresa') ?? $request->query('slug') ?? $request->input('slug');

        if ($slug) {
            return static::where('slug', $slug)->where('activo', true)->first();
        }

        return static::where('activo', true)->first();
    }

    public function platos()
    {
        return $this->hasMany(Plato::class);
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    public function mesas()
    {
        return $this->hasMany(Mesa::class);
    }
}
