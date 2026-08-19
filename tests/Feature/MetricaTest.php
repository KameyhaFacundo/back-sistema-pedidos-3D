<?php

namespace Tests\Feature;

use App\Models\ArVista;
use App\Models\Empresa;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Plato;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MetricaTest extends TestCase
{
    use RefreshDatabase;

    private Empresa $empresa;
    private User $user;
    private Plato $plato;

    protected function setUp(): void
    {
        parent::setUp();

        $this->empresa = Empresa::create(['slug' => 'tuhambur', 'nombre' => 'Tu Hambur', 'activo' => true]);
        $this->user = User::create([
            'empresa_id' => $this->empresa->id,
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
        ]);
        $this->plato = Plato::create([
            'empresa_id' => $this->empresa->id,
            'nombre' => 'Hamburguesa',
            'categoria' => 'principales',
            'precio' => 100,
            'disponible' => true,
        ]);
    }

    public function test_index_devuelve_metricas_del_dia(): void
    {
        $pedido = Pedido::create([
            'empresa_id' => $this->empresa->id,
            'token' => 'token-m',
            'tipo' => 'retiro',
            'medio_pago' => 'efectivo',
            'estado' => 'nuevo',
            'estado_pago' => 'pendiente',
        ]);
        PedidoItem::create(['pedido_id' => $pedido->id, 'plato_id' => $this->plato->id, 'cantidad' => 2, 'subtotal' => 200]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/metricas')
            ->assertOk();

        $this->assertSame(1, $response->json('pedidos_hoy'));
        $this->assertSame('200.00', $response->json('ventas_hoy'));
        $this->assertSame(1, $response->json('activos_ahora'));
        $this->assertSame('Hamburguesa', $response->json('mas_pedido'));
    }

    public function test_registrar_vista_ar_publica(): void
    {
        $this->withHeaders(['X-Empresa' => 'tuhambur'])
            ->postJson("/api/ar-vistas/{$this->plato->id}")
            ->assertOk();

        $this->assertDatabaseHas('ar_vistas', ['plato_id' => $this->plato->id, 'empresa_id' => $this->empresa->id]);
    }
}