<?php

namespace Tests\Feature;

use App\Models\Empresa;
use App\Models\Plato;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PlatoTest extends TestCase
{
    use RefreshDatabase;

    private Empresa $empresa;
    private User $user;

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
    }

    public function test_menu_publico_devuelve_solo_platos_disponibles_de_la_empresa(): void
    {
        Plato::create([
            'empresa_id' => $this->empresa->id,
            'nombre' => 'Hamburguesa',
            'categoria' => 'principales',
            'precio' => 100,
            'disponible' => true,
        ]);
        Plato::create([
            'empresa_id' => $this->empresa->id,
            'nombre' => 'Oculta',
            'categoria' => 'principales',
            'precio' => 50,
            'disponible' => false,
        ]);

        $otraEmpresa = Empresa::create(['slug' => 'otra', 'nombre' => 'Otra', 'activo' => true]);
        Plato::create([
            'empresa_id' => $otraEmpresa->id,
            'nombre' => 'Ajeno',
            'categoria' => 'principales',
            'precio' => 90,
            'disponible' => true,
        ]);

        $response = $this->withHeaders(['X-Empresa' => 'tuhambur'])
            ->getJson('/api/menu')
            ->assertOk();

        $this->assertCount(1, $response->json('platos'));
        $this->assertSame('Hamburguesa', $response->json('platos.0.nombre'));
    }

    public function test_menu_publico_sin_empresa_devuelve_404(): void
    {
        $this->getJson('/api/menu')->assertStatus(404);
    }

    public function test_crear_plato_con_admin(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/platos', [
                'nombre' => 'Milanesa',
                'precio' => 150,
                'categoria' => 'principales',
            ])
            ->assertStatus(201)
            ->assertJsonPath('nombre', 'Milanesa');

        $this->assertDatabaseHas('platos', [
            'empresa_id' => $this->empresa->id,
            'nombre' => 'Milanesa',
            'precio' => 150,
        ]);
    }

    public function test_crear_plato_sin_auth_devuelve_401(): void
    {
        $this->postJson('/api/platos', [
            'nombre' => 'Milanesa',
            'precio' => 150,
        ])->assertStatus(401);
    }

    public function test_crear_plato_valida_categoria(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/platos', [
                'nombre' => 'Raro',
                'precio' => 10,
                'categoria' => 'nada',
            ])
            ->assertStatus(422);
    }

    public function test_index_admin_agrupa_por_empresa(): void
    {
        Plato::create([
            'empresa_id' => $this->empresa->id,
            'nombre' => 'Hamburguesa',
            'categoria' => 'principales',
            'precio' => 100,
            'disponible' => true,
        ]);

        $otraEmpresa = Empresa::create(['slug' => 'otra', 'nombre' => 'Otra', 'activo' => true]);
        Plato::create([
            'empresa_id' => $otraEmpresa->id,
            'nombre' => 'Ajeno',
            'categoria' => 'principales',
            'precio' => 90,
            'disponible' => true,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/platos')
            ->assertOk();

        $this->assertCount(1, $response->json());
        $this->assertSame('Hamburguesa', $response->json('0.nombre'));
    }
}