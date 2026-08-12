<?php

use App\Models\Empresa;
use App\Models\Mesa;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $sinPosicion = Mesa::whereNull('pos_x')->get();

        if ($sinPosicion->isNotEmpty()) {
            $grupos = $sinPosicion->groupBy(fn (Mesa $m) => $m->empresa_id ?? 'global');

            foreach ($grupos as $mesas) {
                $porDefecto = Mesa::posicionesPorDefecto($mesas->count());
                $ordenadas = $mesas->sortBy('numero')->values();

                foreach ($ordenadas as $i => $mesa) {
                    $mesa->update($porDefecto[$i]);
                }
            }
        }

        $empresa = Empresa::where('slug', 'tuhambur')->first();

        if ($empresa && empty($empresa->layout)) {
            $empresa->update([
                'layout' => [
                    ['tipo' => 'entrada', 'x' => 90, 'y' => 46, 'w' => 11, 'h' => 9, 'rotacion' => 0],
                    ['tipo' => 'mostrador', 'x' => 50, 'y' => 86, 'w' => 24, 'h' => 15, 'rotacion' => 0],
                    ['tipo' => 'cocina', 'x' => 90, 'y' => 20, 'w' => 22, 'h' => 18, 'rotacion' => 0],
                    ['tipo' => 'banio', 'x' => 9, 'y' => 87, 'w' => 13, 'h' => 13, 'rotacion' => 0],
                    ['tipo' => 'ventana', 'x' => 50, 'y' => 7, 'w' => 20, 'h' => 8, 'rotacion' => 0],
                ],
            ]);
        }
    }

    public function down(): void
    {
        // No-op: los datos semilla no se revierten con rollback de esquema.
    }
};