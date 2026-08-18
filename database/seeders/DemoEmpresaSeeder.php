<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\Mesa;
use Illuminate\Database\Seeder;

// Empresa dedicada para la demo pública del sitio (landing -> "Probar demo
// interactiva"). Aislada a propósito: no la usa nadie de QA interno, así que
// lo que ve un visitante anónimo no cambia por otro motivo. Solo lectura
// desde el frontend público (GET /menu, GET /mesas) -- nunca se le crean
// pedidos reales, así que a diferencia de DemoSeeder esto es seguro de
// correr contra la base real (firstOrCreate, no trunca nada).
class DemoEmpresaSeeder extends Seeder
{
    public function run(): void
    {
        $empresa = Empresa::firstOrCreate(
            ['slug' => 'demo'],
            ['nombre' => 'Pidevo', 'whatsapp' => '5493815069332', 'activo' => true]
        );

        if ($empresa->mesas()->count() === 0) {
            $posiciones = Mesa::posicionesPorDefecto(6);
            for ($i = 1; $i <= 6; $i++) {
                $empresa->mesas()->create([
                    'numero' => $i,
                    'activa' => true,
                    'pos_x' => $posiciones[$i - 1]['pos_x'],
                    'pos_y' => $posiciones[$i - 1]['pos_y'],
                ]);
            }
        }

        if ($empresa->platos()->count() === 0) {
            $platos = [
                ['nombre' => 'Bunker Cranch Doble', 'categoria' => 'principales', 'descripcion' => 'Doble medallón, cheddar y panceta ahumada', 'precio' => 13500, 'foto' => asset('storage/fotos/hamburguesa.png'), 'disponible' => true],
                ['nombre' => 'Hamburguesa con papas', 'categoria' => 'principales', 'descripcion' => 'Con panceta, cheddar y papas fritas', 'precio' => 12000, 'foto' => asset('storage/fotos/hamburguesa-papas.png'), 'disponible' => true],
                ['nombre' => 'Milanesa napolitana', 'categoria' => 'principales', 'descripcion' => 'Con papas al horno y ensalada', 'precio' => 11000, 'foto' => asset('storage/fotos/napolitana.png'), 'disponible' => true],
                ['nombre' => 'Papas con cheddar', 'categoria' => 'entradas', 'descripcion' => 'Con cebolla caramelizada', 'precio' => 9500, 'disponible' => true],
                ['nombre' => 'Limonada natural', 'categoria' => 'bebidas', 'descripcion' => 'Con menta y jengibre', 'precio' => 2800, 'disponible' => true],
                ['nombre' => 'Flan casero', 'categoria' => 'postres', 'descripcion' => 'Con dulce de leche y crema', 'precio' => 4500, 'disponible' => true],
            ];

            foreach ($platos as $p) {
                $empresa->platos()->create($p);
            }
        }
    }
}
