<?php

namespace Database\Seeders;

use App\Models\Llamado;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Plato;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        PedidoItem::truncate();
        Pedido::truncate();
        Llamado::truncate();
        Plato::truncate();
        Mesa::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $mesas = [];
        $posiciones = Mesa::posicionesPorDefecto(8);
        for ($i = 1; $i <= 8; $i++) {
            $mesas[$i] = Mesa::create([
                'numero' => $i,
                'activa' => true,
                'pos_x' => $posiciones[$i - 1]['pos_x'],
                'pos_y' => $posiciones[$i - 1]['pos_y'],
            ]);
        }

        $platos = [
            ['nombre' => 'Milanesa napolitana', 'categoria' => 'principales', 'descripcion' => 'Con papas fritas y ensalada', 'precio' => 8500, 'disponible' => true],
            ['nombre' => 'Hamburguesa Kamex', 'categoria' => 'principales', 'descripcion' => 'Doble carne, cheddar y bacon', 'precio' => 7200, 'foto' => asset('storage/fotos/hamburguesa.png'), 'modelo_glb' => 'https://modelviewer.dev/shared-assets/models/Astronaut.glb', 'modelo_usdz' => 'https://modelviewer.dev/shared-assets/models/Astronaut.usdz', 'disponible' => true],
            ['nombre' => 'Pizza Margherita', 'categoria' => 'principales', 'descripcion' => 'Muzzarella, tomate y albahaca', 'precio' => 9200, 'disponible' => true],
            ['nombre' => 'Lomo al plomo', 'categoria' => 'principales', 'descripcion' => 'Con papas al horno y chimichurri', 'precio' => 12000, 'disponible' => true],
            ['nombre' => 'Ensalada César', 'categoria' => 'entradas', 'descripcion' => 'Pollo grillado, parmesano y croutones', 'precio' => 5900, 'disponible' => true],
            ['nombre' => 'Rabas fritas', 'categoria' => 'entradas', 'descripcion' => 'Con alioli y limón', 'precio' => 7200, 'disponible' => true],
            ['nombre' => 'Papas con cheddar', 'categoria' => 'entradas', 'descripcion' => 'Con panceta y verdeo', 'precio' => 4500, 'disponible' => false],
            ['nombre' => 'Flan casero', 'categoria' => 'postres', 'descripcion' => 'Con dulce de leche y crema', 'precio' => 4500, 'disponible' => true],
            ['nombre' => 'Brownie con helado', 'categoria' => 'postres', 'descripcion' => 'Helado de americana y chocolate', 'precio' => 5200, 'disponible' => true],
            ['nombre' => 'Limonada natural', 'categoria' => 'bebidas', 'descripcion' => 'Con menta y jengibre', 'precio' => 2800, 'disponible' => true],
            ['nombre' => 'Agua saborizada', 'categoria' => 'bebidas', 'descripcion' => 'De frutos rojos o pomelo', 'precio' => 2200, 'disponible' => true],
            ['nombre' => 'Sorrentinos', 'categoria' => 'principales', 'descripcion' => 'Jamón y queso, salsa rosa', 'precio' => 6800, 'disponible' => true],
        ];

        $platosModels = [];
        foreach ($platos as $p) {
            $platosModels[] = Plato::create($p);
        }

        $milanesa = $platosModels[0];
        $hamburguesa = $platosModels[1];
        $pizza = $platosModels[2];
        $lomo = $platosModels[3];
        $ensalada = $platosModels[4];
        $rabas = $platosModels[5];
        $papas = $platosModels[6];
        $flan = $platosModels[7];
        $brownie = $platosModels[8];
        $limonada = $platosModels[9];
        $agua = $platosModels[10];
        $sorrentinos = $platosModels[11];

        $this->crearPedido('mesa', $mesas[4]->id, 'nuevo', 'efectivo', 'pagado', [
            [$milanesa, 1], [$limonada, 2],
        ], 5);

        $this->crearPedido('retiro', null, 'nuevo', 'transferencia', 'pendiente', [
            [$hamburguesa, 2],
        ], 3);

        $this->crearPedido('mesa', $mesas[2]->id, 'nuevo', 'efectivo', 'pagado', [
            [$lomo, 1], [$agua, 1],
        ], 1);

        $this->crearPedido('mesa', $mesas[6]->id, 'preparacion', 'transferencia', 'pagado', [
            [$pizza, 1], [$ensalada, 1], [$agua, 2],
        ], 12);

        $this->crearPedido('mesa', $mesas[1]->id, 'preparacion', 'efectivo', 'pagado', [
            [$hamburguesa, 1], [$papas, 1], [$limonada, 1],
        ], 8);

        $this->crearPedido('retiro', null, 'listo', 'transferencia', 'pagado', [
            [$sorrentinos, 2], [$flan, 2],
        ], 20);

        $this->crearPedido('mesa', $mesas[3]->id, 'listo', 'efectivo', 'pagado', [
            [$milanesa, 2], [$rabas, 1],
        ], 15);

        $this->crearPedido('mesa', $mesas[4]->id, 'entregado', 'efectivo', 'pagado', [
            [$milanesa, 3], [$brownie, 1],
        ], 35);

        $this->crearPedido('retiro', null, 'entregado', 'transferencia', 'pagado', [
            [$hamburguesa, 1], [$ensalada, 1], [$limonada, 1],
        ], 42);

        $this->crearPedido('mesa', $mesas[2]->id, 'entregado', 'efectivo', 'pagado', [
            [$pizza, 2], [$flan, 1],
        ], 55);

        $this->crearPedido('mesa', $mesas[5]->id, 'entregado', 'efectivo', 'pagado', [
            [$lomo, 1], [$rabas, 1], [$brownie, 1],
        ], 65);

        $this->crearPedido('retiro', null, 'entregado', 'transferencia', 'pagado', [
            [$milanesa, 1], [$sorrentinos, 1],
        ], 78);

        $this->crearPedido('mesa', $mesas[1]->id, 'entregado', 'efectivo', 'pagado', [
            [$hamburguesa, 2], [$agua, 2],
        ], 82);

        $this->crearPedido('mesa', $mesas[3]->id, 'entregado', 'transferencia', 'pagado', [
            [$ensalada, 1], [$limonada, 1],
        ], 95);

        $this->crearPedido('retiro', null, 'entregado', 'efectivo', 'pagado', [
            [$pizza, 1],
        ], 100);

        $this->crearPedido('mesa', $mesas[6]->id, 'entregado', 'efectivo', 'pagado', [
            [$milanesa, 1], [$rabas, 1], [$flan, 1], [$agua, 1],
        ], 110);

        $this->crearPedido('retiro', null, 'entregado', 'transferencia', 'pendiente', [
            [$hamburguesa, 1], [$papas, 1],
        ], 118);

        $this->crearPedido('mesa', $mesas[4]->id, 'entregado', 'efectivo', 'pagado', [
            [$sorrentinos, 1], [$brownie, 1],
        ], 125);

        Llamado::create(['mesa_id' => $mesas[6]->id, 'atendido' => false, 'created_at' => now()->subMinutes(2)]);
        Llamado::create(['mesa_id' => $mesas[2]->id, 'atendido' => false, 'created_at' => now()->subMinute()]);
    }

    private function crearPedido($tipo, $mesaId, $estado, $medioPago, $estadoPago, $items, $minutosAtras)
    {
        $pedido = Pedido::create([
            'tipo' => $tipo,
            'mesa_id' => $mesaId,
            'estado' => $estado,
            'medio_pago' => $medioPago,
            'estado_pago' => $estadoPago,
            'created_at' => now()->subMinutes($minutosAtras),
            'updated_at' => now()->subMinutes(max(1, $minutosAtras - rand(1, 5))),
        ]);

        foreach ($items as [$plato, $cantidad]) {
            PedidoItem::create([
                'pedido_id' => $pedido->id,
                'plato_id' => $plato->id,
                'cantidad' => $cantidad,
                'subtotal' => $plato->precio * $cantidad,
            ]);
        }
    }
}
