<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $rol = $request->user()?->rol ?? 'admin';

        if (!in_array($rol, $roles, true)) {
            abort(403, 'No tenés permiso para realizar esta acción.');
        }

        return $next($request);
    }
}
