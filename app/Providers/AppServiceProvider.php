<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Keyed by email+IP (not just IP) so one attacker can't lock out a
        // real admin by spamming failed logins for their email from a
        // different address, while still throttling brute force per account.
        RateLimiter::for('login', function (Request $request) {
            $key = strtolower((string) $request->input('email')) . '|' . $request->ip();

            return Limit::perMinute(5)->by($key);
        });

        RateLimiter::for('registro', function (Request $request) {
            return Limit::perHour(5)->by($request->ip());
        });

        // A client ordering from a table/queue can legitimately place a few
        // orders in a row, but not hundreds per minute. Keyed by empresa+IP so
        // one shared connection/IP can't be used to spam every endpoint.
        RateLimiter::for('pedidos', function (Request $request) {
            $empresa = $request->header('X-Empresa') ?? 'anon';

            return Limit::perMinute(10)->by($empresa . '|' . $request->ip());
        });

        RateLimiter::for('cupones', function (Request $request) {
            $empresa = $request->header('X-Empresa') ?? 'anon';

            return Limit::perMinute(20)->by($empresa . '|' . $request->ip());
        });
    }
}
