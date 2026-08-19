<?php

namespace Tests\Feature;

use App\Models\Empresa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistroTest extends TestCase
{
    use RefreshDatabase;

    public function test_registro_crea_empresa_usuario_y_15_mesas(): void
    {
        $response = $this->postJson('/api/registro', [
            'empresa' => 'Mi Local',
            'nombre' => 'Dueno',
            'email' => 'dueno@test.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['token', 'empresa' => ['slug'], 'user' => ['email']])
            ->assertJsonPath('user.email', 'dueno@test.com');

        $this->assertDatabaseHas('empresas', ['nombre' => 'Mi Local', 'activo' => true]);
        $this->assertDatabaseHas('users', ['email' => 'dueno@test.com']);

        $empresa = Empresa::where('slug', 'mi-local')->first();
        $this->assertNotNull($empresa);
        $this->assertSame(15, $empresa->mesas()->count());
    }

    public function test_registro_evita_slug_reservado(): void
    {
        $this->postJson('/api/registro', [
            'empresa' => 'admin',
            'nombre' => 'Dueno',
            'email' => 'dueno@test.com',
            'password' => 'secret123',
        ])->assertStatus(201);

        $this->assertDatabaseMissing('empresas', ['slug' => 'admin']);
        $this->assertDatabaseHas('empresas', ['slug' => 'admin-1']);
    }

    public function test_registro_con_email_duplicado_devuelve_422(): void
    {
        $this->postJson('/api/registro', [
            'empresa' => 'A',
            'nombre' => 'Dueno',
            'email' => 'dueno@test.com',
            'password' => 'secret123',
        ])->assertStatus(201);

        $this->postJson('/api/registro', [
            'empresa' => 'B',
            'nombre' => 'Otro',
            'email' => 'dueno@test.com',
            'password' => 'secret123',
        ])->assertStatus(422);
    }
}