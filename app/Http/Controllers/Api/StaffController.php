<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    private function empresa()
    {
        $empresa = auth()->user()?->empresa;

        if (!$empresa) {
            abort(404, 'Empresa no encontrada');
        }

        return $empresa;
    }

    public function index()
    {
        $empresa = $this->empresa();

        $users = User::where('empresa_id', $empresa->id)
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'rol', 'created_at']);

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $empresa = $this->empresa();

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => 'required|string|min:6',
            'rol' => ['nullable', Rule::in(['admin', 'cocina', 'mozo'])],
        ]);

        $user = User::create([
            'empresa_id' => $empresa->id,
            'name' => $validated['nombre'],
            'email' => strtolower($validated['email']),
            'password' => $validated['password'],
            'rol' => $validated['rol'] ?? 'cocina',
        ]);

        return response()->json(['id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'rol' => $user->rol], 201);
    }

    public function destroy(Request $request, User $user)
    {
        $empresa = $this->empresa();

        if ($user->id === $request->user()->id) {
            return response()->json(['message' => 'No podés eliminarte a vos mismo.'], 422);
        }

        if ($user->empresa_id !== $empresa->id) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->tokens()->delete();
        $user->delete();

        return response()->json(['message' => 'Usuario eliminado']);
    }
}