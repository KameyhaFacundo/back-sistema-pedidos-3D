<?php

namespace Database\Seeders;

use App\Models\Plato;
use Illuminate\Database\Seeder;

class PlatoSeeder extends Seeder
{
    public function run(): void
    {
        $platos = [
            ['nombre' => 'Milanesa napolitana', 'categoria' => 'principales', 'descripcion' => 'Con papas fritas y ensalada', 'precio' => 8500, 'foto' => asset('storage/fotos/napolitana.png'), 'disponible' => true],
            ['nombre' => 'Hamburguesa Kamex', 'categoria' => 'principales', 'descripcion' => 'Doble carne, cheddar y bacon', 'precio' => 7200, 'foto' => asset('storage/fotos/hamburguesa.png'), 'disponible' => true],
            ['nombre' => 'Pizza Margherita', 'categoria' => 'principales', 'descripcion' => 'Muzzarella, tomate y albahaca fresca', 'precio' => 9200, 'disponible' => true],
            ['nombre' => 'Lomo al plomo', 'categoria' => 'principales', 'descripcion' => 'Con papas al horno y chimichurri', 'precio' => 12000, 'foto' => asset('storage/fotos/hamburguesa-papas.png'), 'disponible' => true],
            ['nombre' => 'Ensalada César', 'categoria' => 'entradas', 'descripcion' => 'Pollo grillado, parmesano y croutones', 'precio' => 5900, 'disponible' => true],
            ['nombre' => 'Rabas fritas', 'categoria' => 'entradas', 'descripcion' => 'Con alioli y limón', 'precio' => 7200, 'disponible' => true],
            ['nombre' => 'Sorrentinos', 'categoria' => 'principales', 'descripcion' => 'De jamón y queso, con salsa rosa', 'precio' => 6800, 'disponible' => true],
            ['nombre' => 'Flan casero', 'categoria' => 'postres', 'descripcion' => 'Con dulce de leche y crema', 'precio' => 4500, 'disponible' => true],
            ['nombre' => 'Brownie con helado', 'categoria' => 'postres', 'descripcion' => 'Helado de americana y salsa de chocolate', 'precio' => 5200, 'disponible' => true],
            ['nombre' => 'Limonada natural', 'categoria' => 'bebidas', 'descripcion' => 'Con menta y jengibre', 'precio' => 2800, 'disponible' => true],
            ['nombre' => 'Agua saborizada', 'categoria' => 'bebidas', 'descripcion' => 'De frutos rojos o pomelo', 'precio' => 2200, 'disponible' => true],
            ['nombre' => 'Papas fritas', 'categoria' => 'entradas', 'descripcion' => 'Con cheddar y panceta', 'precio' => 4500, 'disponible' => false],
        ];

        foreach ($platos as $plato) {
            Plato::create($plato);
        }
    }
}
