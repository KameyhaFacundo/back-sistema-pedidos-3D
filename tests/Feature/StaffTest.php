<?php

namespace Tests\Feature;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StaffTest extends TestCase
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

    public function test_invitar_staff(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/staff', [
                'nombre' => 'Cocinero',
                'email' => 'cocina@test.com',
                'password' => 'secret123',
            ])
            ->assertStatus(201)
            ->assertJsonPath('email', 'cocina@test.com');

        $this->assertDatabaseHas('users', ['empresa_id' => $this->empresa->id, 'email' => 'cocina@test.com']);
    }

    public function test_listar_staff(): void
    {
        User::create([
            'empresa_id' => $this->empresa->id,
            'name' => 'Cocinero',
            'email' => 'cocina@test.com',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/staff')
            ->assertOk();

        $this->assertCount(2, $response->json());
    }

    public function test_eliminar_staff(): void
    {
        $staff = User::create([
            'empresa_id' => $this->empresa->id,
            'name' => 'Cocinero',
            'email' => 'cocina@test.com',
            'password' => Hash::make('secret123'),
        ]);

        $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/staff/{$staff->id}")
            ->assertOk();

        $this->assertDatabaseMissing('users', ['id' => $staff->id]);
    }

    public function test_no_puede_eliminarse_a_si_mismo(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/staff/{$this->user->id}")
            ->assertStatus(422);
    }

    public function test_staff_sin_auth_devuelve_401(): void
    {
        $this->postJson('/api/staff', [
            'nombre' => 'X',
            'email' => 'x@test.com',
            'password' => 'secret123',
        ])->assertStatus(401);
    }
}