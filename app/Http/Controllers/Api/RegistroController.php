<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Mesa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RegistroController extends Controller
{
    // Must match the RESERVED list in the frontend's CompanyContext.jsx --
    // those path segments are treated as app routes (/admin, /login, etc),
    // not company slugs, so a company can't be reached at a slug on this list.
    private const RESERVED_SLUGS = ['admin', 'cocina', 'llamados', 'login', 'landing', 'demo'];

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

        $slug = isset($validated['slug']) && $validated['slug']
            ? Str::slug($validated['slug'])
            : Str::slug($validated['empresa']);

        $slug = $slug ?: 'local';

        // Asegurar unicidad del slug y que no choque con una ruta reservada
        // de la app (/admin, /login, etc.), que dejaría a la empresa
        // inalcanzable por su propio slug.
        $baseSlug = $slug;
        $i = 1;
        while (Empresa::where('slug', $slug)->exists() || in_array($slug, self::RESERVED_SLUGS, true)) {
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
            $posiciones = Mesa::posicionesPorDefecto(15);
            for ($i = 1; $i <= 15; $i++) {
                $empresa->mesas()->create([
                    'numero' => $i,
                    'activa' => true,
                    'pos_x' => $posiciones[$i - 1]['pos_x'],
                    'pos_y' => $posiciones[$i - 1]['pos_y'],
                ]);
            }

            $token = $user->createToken('admin-token', ['*'])->plainTextToken;

            return ['empresa' => $empresa, 'user' => $user, 'token' => $token];
        });

        return response()->json($result, 201);
    }
}
