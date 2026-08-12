<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RegistroController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'empresa' => 'required|string|max:100',
            'slug' => 'nullable|string|max:60',
            'nombre' => 'required|string|max:100',
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'whatsapp' => 'nullable|string|max:30',
            'password' => 'required|string|min:6',
        ]);

        $slug = $validated['slug']
            ? Str::slug($validated['slug'])
            : Str::slug($validated['empresa']);

        $slug = $slug ?: 'local';

        // Asegurar unicidad del slug
        $baseSlug = $slug;
        $i = 1;
        while (Empresa::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i;
            $i++;
        }

        $result = DB::transaction(function () use ($validated, $slug) {
            $empresa = Empresa::create([
                'slug' => $slug,
                'nombre' => $validated['empresa'],
                'whatsapp' => $validated['whatsapp'] ?? null,
                'activo' => true,
            ]);

            $user = User::create([
                'empresa_id' => $empresa->id,
                'name' => $validated['nombre'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
            ]);

            // Mesas por defecto
            for ($i = 1; $i <= 15; $i++) {
                $empresa->mesas()->create(['numero' => $i, 'activa' => true]);
            }

            $token = $user->createToken('admin-token', ['*'])->plainTextToken;

            return ['empresa' => $empresa, 'user' => $user, 'token' => $token];
        });

        return response()->json($result, 201);
    }
}
