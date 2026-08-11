<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class TokenFromQuery
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('token') && !$request->bearerToken()) {
            $token = PersonalAccessToken::findToken($request->get('token'));
            if ($token && $token->tokenable) {
                auth()->setUser($token->tokenable);
            }
        }

        return $next($request);
    }
}
