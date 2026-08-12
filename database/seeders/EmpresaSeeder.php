<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\Mesa;
use App\Models\Plato;
use App\Models\User;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        $empresa = Empresa::firstOrCreate(
            ['slug' => 'tuhambur'],
            ['nombre' => 'Tu Hambur', 'whatsapp' => '5493815069332', 'activo' => true]
        );

        // Mesas para la empresa
        if ($empresa->mesas()->count() === 0) {
            for ($i = 1; $i <= 15; $i++) {
                $empresa->mesas()->create(['numero' => $i, 'activa' => true]);
            }
        }

        // Platos demo para la empresa
        if ($empresa->platos()->count() === 0) {
            $platos = [
                ['nombre' => 'Bunker Cranch Doble', 'categoria' => 'principales', 'descripcion' => 'Doble medallón de 110gr, queso tybo, panceta ahumada', 'precio' => 13500, 'foto' => asset('storage/fotos/hamburguesa.png'), 'disponible' => true],
                ['nombre' => 'Hamburguesa Kamex', 'categoria' => 'principales', 'descripcion' => 'Doble carne, cheddar y bacon', 'precio' => 7200, 'foto' => asset('storage/fotos/hamburguesa.png'), 'disponible' => true],
                ['nombre' => 'Papas con cheddar', 'categoria' => 'entradas', 'descripcion' => 'Con cheddar y panceta', 'precio' => 9500, 'disponible' => true],
                ['nombre' => 'Milanesa napolitana', 'categoria' => 'principales', 'descripcion' => 'Con papas fritas y ensalada', 'precio' => 8500, 'foto' => asset('storage/fotos/napolitana.png'), 'disponible' => true],
                ['nombre' => 'Limonada natural', 'categoria' => 'bebidas', 'descripcion' => 'Con menta y jengibre', 'precio' => 2800, 'disponible' => true],
                ['nombre' => 'Flan casero', 'categoria' => 'postres', 'descripcion' => 'Con dulce de leche y crema', 'precio' => 4500, 'disponible' => true],
            ];

            foreach ($platos as $p) {
                $empresa->platos()->create($p);
            }
        }

        // Usuario dueño de la empresa
        User::firstOrCreate(
            ['email' => 'admin@pidevo.com'],
            [
                'empresa_id' => $empresa->id,
                'name' => 'Admin',
                'password' => bcrypt('admin123'),
            ]
        );
    }
}
