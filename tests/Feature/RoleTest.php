<?php

namespace Tests\Feature;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    private Empresa $empresa;

    protected function setUp(): void
    {
        parent::setUp();

        $this->empresa = Empresa::create(['slug' => 'tuhambur', 'nombre' => 'Tu Hambur', 'activo' => true]);
    }

    private function userConRol(string $rol): User
    {
        return User::create([
            'empresa_id' => $this->empresa->id,
            'name' => ucfirst($rol),
            'email' => "{$rol}@test.com",
            'password' => Hash::make('password'),
            'rol' => $rol,
        ]);
    }

    public function test_invitar_staff_con_rol(): void
    {
        $admin = $this->userConRol('admin');

        $this->actingAs($admin, 'sanctum')
            ->postJson('/api/staff', [
                'nombre' => 'Cocinero',
                'email' => 'cocina2@test.com',
                'password' => 'secret123',
                'rol' => 'cocina',
            ])
            ->assertStatus(201)
            ->assertJsonPath('rol', 'cocina');
    }

    public function test_cocina_puede_ver_y_avanzar_pedidos(): void
    {
        $cocina = $this->userConRol('cocina');

        $this->actingAs($cocina, 'sanctum')->getJson('/api/pedidos')->assertOk();
    }

    public function test_cocina_no_puede_ver_metricas_ni_equipo(): void
    {
        $cocina = $this->userConRol('cocina');

        $this->actingAs($cocina, 'sanctum')->getJson('/api/metricas')->assertStatus(403);
        $this->actingAs($cocina, 'sanctum')->getJson('/api/staff')->assertStatus(403);
        $this->actingAs($cocina, 'sanctum')->getJson('/api/llamados')->assertStatus(403);
    }

    public function test_mozo_puede_ver_llamados_pero_no_pedidos_ni_metricas(): void
    {
        $mozo = $this->userConRol('mozo');

        $this->actingAs($mozo, 'sanctum')->getJson('/api/llamados')->assertOk();
        $this->actingAs($mozo, 'sanctum')->getJson('/api/pedidos')->assertStatus(403);
        $this->actingAs($mozo, 'sanctum')->getJson('/api/metricas')->assertStatus(403);
    }

    public function test_admin_accede_a_todo(): void
    {
        $admin = $this->userConRol('admin');

        $this->actingAs($admin, 'sanctum')->getJson('/api/pedidos')->assertOk();
        $this->actingAs($admin, 'sanctum')->getJson('/api/llamados')->assertOk();
        $this->actingAs($admin, 'sanctum')->getJson('/api/metricas')->assertOk();
        $this->actingAs($admin, 'sanctum')->getJson('/api/staff')->assertOk();
    }
}
