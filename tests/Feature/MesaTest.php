<?php

namespace Tests\Feature;

use App\Models\Empresa;
use App\Models\Llamado;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MesaTest extends TestCase
{
    use RefreshDatabase;

    private Empresa $empresa;
    private User $user;
    private Mesa $mesa;

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
        $this->mesa = Mesa::create([
            'empresa_id' => $this->empresa->id,
            'numero' => 1,
            'forma' => 'circular',
            'activa' => true,
            'pos_x' => 10,
            'pos_y' => 10,
        ]);
    }

    public function test_index_publico_devuelve_mesas_con_estado_ocupada(): void
    {
        Pedido::create([
            'empresa_id' => $this->empresa->id,
            'token' => 'token-x',
            'tipo' => 'mesa',
            'mesa_id' => $this->mesa->id,
            'medio_pago' => 'efectivo',
            'estado' => 'nuevo',
            'estado_pago' => 'pendiente',
        ]);

        $response = $this->withHeaders(['X-Empresa' => 'tuhambur'])
            ->getJson('/api/mesas')
            ->assertOk();

        $this->assertCount(1, $response->json());
        $this->assertTrue($response->json('0.ocupada'));
    }

    public function test_llamar_mozo_publico_crea_un_llamado(): void
    {
        $this->withHeaders(['X-Empresa' => 'tuhambur'])
            ->postJson("/api/mesas/{$this->mesa->id}/llamar")
            ->assertOk();

        $this->assertDatabaseHas('llamados', ['empresa_id' => $this->empresa->id, 'mesa_id' => $this->mesa->id, 'atendido' => false]);
    }

    public function test_llamar_mozo_de_otra_empresa_devuelve_404(): void
    {
        $otra = Empresa::create(['slug' => 'otra', 'nombre' => 'Otra', 'activo' => true]);
        $mesaAjenae = Mesa::create(['empresa_id' => $otra->id, 'numero' => 1, 'activa' => true]);

        $this->withHeaders(['X-Empresa' => 'tuhambur'])
            ->postJson("/api/mesas/{$mesaAjenae->id}/llamar")
            ->assertStatus(404);
    }

    public function test_listar_y_atender_llamados(): void
    {
        Llamado::create(['empresa_id' => $this->empresa->id, 'mesa_id' => $this->mesa->id, 'atendido' => false]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/llamados')
            ->assertOk();

        $this->assertCount(1, $response->json());
        $id = $response->json('0.id');

        $this->actingAs($this->user, 'sanctum')
            ->patchJson("/api/llamados/{$id}/atender")
            ->assertOk();

        $this->assertDatabaseHas('llamados', ['id' => $id, 'atendido' => true]);
    }

    public function test_crear_mesa_con_admin(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/mesas', ['numero' => 2, 'forma' => 'rectangular'])
            ->assertStatus(201)
            ->assertJsonPath('numero', 2);
    }

    public function test_toggle_y_eliminar_mesa(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->patchJson("/api/mesas/{$this->mesa->id}/toggle")
            ->assertOk()
            ->assertJsonPath('activa', false);

        $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/mesas/{$this->mesa->id}")
            ->assertOk();

        $this->assertDatabaseMissing('mesas', ['id' => $this->mesa->id]);
    }

    public function test_no_puede_modificar_mesa_de_otra_empresa(): void
    {
        $otra = Empresa::create(['slug' => 'otra', 'nombre' => 'Otra', 'activo' => true]);
        $mesaAjenae = Mesa::create(['empresa_id' => $otra->id, 'numero' => 1, 'activa' => true]);

        $this->actingAs($this->user, 'sanctum')
            ->patchJson("/api/mesas/{$mesaAjenae->id}/toggle")
            ->assertStatus(403);
    }
}