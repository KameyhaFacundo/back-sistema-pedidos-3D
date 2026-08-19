<?php

namespace Tests\Feature;

use App\Models\Empresa;
use App\Models\Pedido;
use App\Models\Plato;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PedidoTest extends TestCase
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

    private function crearPedido(string $estado = 'nuevo'): Pedido
    {
        return Pedido::create([
            'empresa_id' => $this->empresa->id,
            'token' => 'token-test-' . \Illuminate\Support\Str::random(8),
            'tipo' => 'retiro',
            'medio_pago' => 'efectivo',
            'estado' => $estado,
            'estado_pago' => 'pendiente',
        ]);
    }

    public function test_crear_pedido_de_retiro(): void
    {
        $response = $this->withHeaders(['X-Empresa' => 'tuhambur'])->postJson('/api/pedidos', [
            'tipo' => 'retiro',
            'medio_pago' => 'efectivo',
            'items' => [
                ['plato_id' => $this->plato->id, 'cantidad' => 2],
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('estado', 'nuevo')
            ->assertJsonPath('tipo', 'retiro');

        $this->assertDatabaseHas('pedidos', ['empresa_id' => $this->empresa->id]);
        $this->assertDatabaseHas('pedido_items', [
            'plato_id' => $this->plato->id,
            'cantidad' => 2,
            'subtotal' => 200,
        ]);
    }

    public function test_crear_pedido_requiere_items(): void
    {
        $this->withHeaders(['X-Empresa' => 'tuhambur'])->postJson('/api/pedidos', [
            'tipo' => 'retiro',
            'medio_pago' => 'efectivo',
            'items' => [],
        ])->assertStatus(422)->assertJsonValidationErrors('items');
    }

    public function test_crear_pedido_sin_empresa_resuelta_devuelve_404(): void
    {
        $this->postJson('/api/pedidos', [
            'tipo' => 'retiro',
            'medio_pago' => 'efectivo',
            'items' => [['plato_id' => $this->plato->id, 'cantidad' => 1]],
        ])->assertStatus(404);
    }

    public function test_crear_pedido_de_mesa_requiere_mesa_id(): void
    {
        $this->withHeaders(['X-Empresa' => 'tuhambur'])->postJson('/api/pedidos', [
            'tipo' => 'mesa',
            'medio_pago' => 'efectivo',
            'items' => [['plato_id' => $this->plato->id, 'cantidad' => 1]],
        ])->assertStatus(422);
    }

    public function test_flujo_completo_de_estados(): void
    {
        $pedido = $this->crearPedido();

        $this->actingAs($this->user, 'sanctum')
            ->patchJson("/api/pedidos/{$pedido->id}/estado", ['estado' => 'preparacion'])
            ->assertOk()->assertJsonPath('estado', 'preparacion');

        $this->actingAs($this->user, 'sanctum')
            ->patchJson("/api/pedidos/{$pedido->id}/estado", ['estado' => 'listo'])
            ->assertOk()->assertJsonPath('estado', 'listo');

        $this->actingAs($this->user, 'sanctum')
            ->patchJson("/api/pedidos/{$pedido->id}/estado", ['estado' => 'entregado'])
            ->assertOk()->assertJsonPath('estado', 'entregado');
    }

    public function test_transicion_invalida_devuelve_422(): void
    {
        $pedido = $this->crearPedido();

        $this->actingAs($this->user, 'sanctum')
            ->patchJson("/api/pedidos/{$pedido->id}/estado", ['estado' => 'entregado'])
            ->assertStatus(422);
    }

    public function test_cancelar_pedido_nuevo(): void
    {
        $pedido = $this->crearPedido();

        $this->actingAs($this->user, 'sanctum')
            ->patchJson("/api/pedidos/{$pedido->id}/cancelar")
            ->assertOk()
            ->assertJsonPath('estado', 'cancelado');
    }

    public function test_no_se_puede_cancelar_un_pedido_entregado(): void
    {
        $pedido = $this->crearPedido('entregado');

        $this->actingAs($this->user, 'sanctum')
            ->patchJson("/api/pedidos/{$pedido->id}/cancelar")
            ->assertStatus(422);
    }

    public function test_marcar_pedido_como_pagado(): void
    {
        $pedido = $this->crearPedido();

        $this->actingAs($this->user, 'sanctum')
            ->patchJson("/api/pedidos/{$pedido->id}/pago")
            ->assertOk()
            ->assertJsonPath('estado_pago', 'pagado');
    }

    public function test_index_filtra_por_la_empresa_del_admin(): void
    {
        $this->crearPedido();

        $otraEmpresa = Empresa::create(['slug' => 'otra', 'nombre' => 'Otra', 'activo' => true]);
        Pedido::create([
            'empresa_id' => $otraEmpresa->id,
            'token' => 'token-otra',
            'tipo' => 'retiro',
            'medio_pago' => 'efectivo',
            'estado' => 'nuevo',
            'estado_pago' => 'pendiente',
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/pedidos')
            ->assertOk();

        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertSame(1, $data[0]['id']);
        $this->assertSame('retiro', $data[0]['tipo']);
    }

    public function test_show_requiere_token_para_acceso_publico(): void
    {
        $pedido = $this->crearPedido();

        $this->getJson("/api/pedidos/{$pedido->id}")
            ->assertStatus(404);

        $this->getJson("/api/pedidos/{$pedido->id}?token=" . $pedido->token)
            ->assertOk()
            ->assertJsonPath('estado', 'nuevo');
    }

    public function test_index_filtra_por_estado(): void
    {
        $this->crearPedido('nuevo');
        $this->crearPedido('listo');

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/pedidos?estado=listo')
            ->assertOk();

        $this->assertCount(1, $response->json('data'));
        $this->assertSame('listo', $response->json('data.0.estado'));
    }
}