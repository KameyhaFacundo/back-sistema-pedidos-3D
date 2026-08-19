<?php

namespace Tests\Feature;

use App\Models\Cupon;
use App\Models\Empresa;
use App\Models\Pedido;
use App\Models\Plato;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CuponTest extends TestCase
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

    public function test_validar_cupon_publico_resuelve_empresa_por_slug(): void
    {
        Cupon::create(['empresa_id' => $this->empresa->id, 'codigo' => 'BIEN10', 'descuento' => 10, 'tipo' => 'fijo', 'activo' => true]);

        $this->withHeaders(['X-Empresa' => 'tuhambur'])
            ->postJson('/api/cupones/validar', ['codigo' => 'BIEN10'])
            ->assertOk()
            ->assertJsonPath('codigo', 'BIEN10');
    }

    public function test_validar_cupon_de_otra_empresa_devuelve_422(): void
    {
        $otra = Empresa::create(['slug' => 'otra', 'nombre' => 'Otra', 'activo' => true]);
        Cupon::create(['empresa_id' => $otra->id, 'codigo' => 'AJENO', 'descuento' => 5, 'tipo' => 'fijo', 'activo' => true]);

        $this->withHeaders(['X-Empresa' => 'tuhambur'])
            ->postJson('/api/cupones/validar', ['codigo' => 'AJENO'])
            ->assertStatus(422);
    }

    public function test_cupon_inactivo_no_es_valido(): void
    {
        Cupon::create(['empresa_id' => $this->empresa->id, 'codigo' => 'OFF', 'descuento' => 5, 'tipo' => 'fijo', 'activo' => false]);

        $this->withHeaders(['X-Empresa' => 'tuhambur'])
            ->postJson('/api/cupones/validar', ['codigo' => 'OFF'])
            ->assertStatus(422);
    }

    public function test_aplicar_cupon_fijo_en_el_pedido(): void
    {
        Cupon::create(['empresa_id' => $this->empresa->id, 'codigo' => 'REBAJA', 'descuento' => 30, 'tipo' => 'fijo', 'activo' => true]);

        $response = $this->withHeaders(['X-Empresa' => 'tuhambur'])->postJson('/api/pedidos', [
            'tipo' => 'retiro',
            'medio_pago' => 'efectivo',
            'cupon_codigo' => 'REBAJA',
            'items' => [['plato_id' => $this->plato->id, 'cantidad' => 1]],
        ]);

        $response->assertStatus(201)->assertJsonPath('descuento', 30);
        $this->assertDatabaseHas('pedidos', ['descuento' => 30]);
    }

    public function test_aplicar_cupon_porcentaje_en_el_pedido(): void
    {
        Cupon::create(['empresa_id' => $this->empresa->id, 'codigo' => 'PCT', 'descuento' => 10, 'tipo' => 'porcentaje', 'activo' => true]);

        $response = $this->withHeaders(['X-Empresa' => 'tuhambur'])->postJson('/api/pedidos', [
            'tipo' => 'retiro',
            'medio_pago' => 'efectivo',
            'cupon_codigo' => 'PCT',
            'items' => [['plato_id' => $this->plato->id, 'cantidad' => 2]],
        ]);

        $response->assertStatus(201)->assertJsonPath('descuento', 20);
    }

    public function test_crear_cupon_con_admin(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/cupones', ['codigo' => 'nuevo10', 'descuento' => 10, 'tipo' => 'fijo'])
            ->assertStatus(201)
            ->assertJsonPath('codigo', 'NUEVO10');

        $this->assertDatabaseHas('cupones', ['codigo' => 'NUEVO10', 'empresa_id' => $this->empresa->id]);
    }

    public function test_cupon_duplicado_por_empresa_devuelve_422(): void
    {
        Cupon::create(['empresa_id' => $this->empresa->id, 'codigo' => 'DUP', 'descuento' => 5, 'tipo' => 'fijo', 'activo' => true]);

        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/cupones', ['codigo' => 'dup', 'descuento' => 5, 'tipo' => 'fijo'])
            ->assertStatus(422);
    }

    public function test_toggle_y_eliminar_cupon(): void
    {
        $cupon = Cupon::create(['empresa_id' => $this->empresa->id, 'codigo' => 'X', 'descuento' => 5, 'tipo' => 'fijo', 'activo' => true]);

        $this->actingAs($this->user, 'sanctum')
            ->patchJson("/api/cupones/{$cupon->id}/toggle")
            ->assertOk()
            ->assertJsonPath('activo', false);

        $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/cupones/{$cupon->id}")
            ->assertOk();

        $this->assertDatabaseMissing('cupones', ['id' => $cupon->id]);
    }

    public function test_no_puede_tocar_cupon_de_otra_empresa(): void
    {
        $otra = Empresa::create(['slug' => 'otra', 'nombre' => 'Otra', 'activo' => true]);
        $cupon = Cupon::create(['empresa_id' => $otra->id, 'codigo' => 'AJENO', 'descuento' => 5, 'tipo' => 'fijo', 'activo' => true]);

        $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/cupones/{$cupon->id}")
            ->assertStatus(404);
    }
}