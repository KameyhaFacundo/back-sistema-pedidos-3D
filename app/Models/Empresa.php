<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Empresa extends Model
{
    protected $fillable = ['slug', 'nombre', 'whatsapp', 'logo', 'activo', 'abierto', 'horarios', 'tiempo_estimado', 'layout'];

    protected $casts = [
        'activo' => 'boolean',
        'abierto' => 'boolean',
        'horarios' => 'array',
        'tiempo_estimado' => 'integer',
        'layout' => 'array',
    ];

    const DIAS = ['dom', 'lun', 'mar', 'mie', 'jue', 'vie', 'sab'];

    public function estaAbiertaAhora(): bool
    {
        $horarios = $this->horarios;

        if (empty($horarios) || !is_array($horarios)) {
            return (bool) $this->abierto;
        }

        $dias = static::DIAS;
        $hoy = $dias[(int) now()->format('w')];
        $rango = $horarios[$hoy] ?? null;

        if (!$rango) {
            return false;
        }

        $hora = now()->format('H:i');

        foreach (explode(',', (string) $rango) as $bloque) {
            $partes = array_map('trim', explode('-', $bloque));
            if (count($partes) !== 2 || $partes[0] === '' || $partes[1] === '') {
                continue;
            }
            if ($hora >= $partes[0] && $hora <= $partes[1]) {
                return true;
            }
        }

        return false;
    }

    public static function resolveFromRequest(Request $request): ?self
    {
        $slug = $request->header('X-Empresa') ?? $request->query('slug') ?? $request->input('slug');

        if ($slug) {
            return static::where('slug', $slug)->where('activo', true)->first();
        }

        if (auth()->check() && auth()->user()->empresa_id) {
            return auth()->user()->empresa;
        }

        return null;
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
