<?php

namespace Tests\Feature;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EmpresaTest extends TestCase
{
    use RefreshDatabase;

    private Empresa $empresa;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->empresa = Empresa::create(['slug' => 'tuhambur', 'nombre' => 'Tu Hambur', 'whatsapp' => '5491100000000', 'activo' => true]);
        $this->user = User::create([
            'empresa_id' => $this->empresa->id,
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
        ]);
    }

    public function test_show_publico_por_slug(): void
    {
        $this->withHeaders(['X-Empresa' => 'tuhambur'])
            ->getJson('/api/empresa')
            ->assertOk()
            ->assertJsonPath('nombre', 'Tu Hambur')
            ->assertJsonPath('slug', 'tuhambur');
    }

    public function test_show_publico_sin_slug_devuelve_404(): void
    {
        $this->getJson('/api/empresa')->assertStatus(404);
    }

    public function test_update_con_admin(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->putJson('/api/empresa', ['nombre' => 'Nuevo Nombre', 'whatsapp' => '5491199999999'])
            ->assertOk()
            ->assertJsonPath('nombre', 'Nuevo Nombre')
            ->assertJsonPath('whatsapp', '5491199999999');
    }

    public function test_update_layout_con_admin(): void
    {
        $layout = [
            ['tipo' => 'mesa', 'x' => 10, 'y' => 20, 'w' => 8, 'h' => 8, 'rotacion' => 0],
            ['tipo' => 'barra', 'x' => 50, 'y' => 50, 'w' => 30, 'h' => 6],
        ];

        $this->actingAs($this->user, 'sanctum')
            ->putJson('/api/empresa/layout', ['layout' => $layout])
            ->assertOk();

        $this->assertSame($layout, Empresa::find($this->empresa->id)->layout);
    }

    public function test_update_sin_auth_devuelve_401(): void
    {
        $this->putJson('/api/empresa', ['nombre' => 'X'])->assertStatus(401);
    }
}