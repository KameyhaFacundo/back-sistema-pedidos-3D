<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CocinaController;
use App\Http\Controllers\Api\CuponController;
use App\Http\Controllers\Api\EmpresaController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\MesaController;
use App\Http\Controllers\Api\MetricaController;
use App\Http\Controllers\Api\PedidoController;
use App\Http\Controllers\Api\PlatoController;
use App\Http\Controllers\Api\PagoController;
use App\Http\Controllers\Api\RegistroController;
use App\Http\Controllers\Api\SSEController;
use App\Http\Controllers\Api\StaffController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->name('login')->middleware('throttle:login');

Route::post('registro', [RegistroController::class, 'store'])->middleware('throttle:registro');

Route::get('empresa', [EmpresaController::class, 'show']);

Route::get('menu', [MenuController::class, 'index']);

Route::post('ar-vistas/{plato}', [\App\Http\Controllers\Api\MetricaController::class, 'registrarVista']);

Route::get('mesas', [MesaController::class, 'index']);
Route::post('mesas/{mesa}/llamar', [MesaController::class, 'llamar']);

Route::post('pedidos', [PedidoController::class, 'store'])->middleware('throttle:pedidos');
Route::get('pedidos/{pedido}', [PedidoController::class, 'show']);
Route::post('cupones/validar', [PedidoController::class, 'validarCupon'])->middleware('throttle:cupones');

Route::post('pagos/mp/preference', [PagoController::class, 'preference'])->middleware('throttle:pedidos');
Route::post('pagos/mp/webhook', [PagoController::class, 'webhook']);

Route::get('sse', [SSEController::class, 'stream'])->middleware('token.query');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    // Cocina gestiona la cola de pedidos; el cobro (pago) queda solo para admin.
    Route::middleware('role:admin,cocina')->group(function () {
        Route::get('pedidos', [PedidoController::class, 'index']);
        Route::patch('pedidos/{pedido}/estado', [PedidoController::class, 'updateEstado']);
        Route::patch('pedidos/{pedido}/cancelar', [PedidoController::class, 'cancelar']);
    });
    Route::patch('pedidos/{pedido}/pago', [PedidoController::class, 'updatePago'])->middleware('role:admin');

    // Mozo atiende los llamados de mesa.
    Route::middleware('role:admin,mozo')->group(function () {
        Route::get('llamados', [CocinaController::class, 'llamados']);
        Route::patch('llamados/{llamado}/atender', [CocinaController::class, 'atenderLlamado']);
    });

    // El resto de la administración (config, menú, mesas, cupones, equipo) es solo del dueño/admin.
    Route::middleware('role:admin')->group(function () {
        Route::get('metricas', [MetricaController::class, 'index']);

        Route::post('mesas', [MesaController::class, 'store']);
        Route::put('mesas/{mesa}', [MesaController::class, 'update']);
        Route::delete('mesas/{mesa}', [MesaController::class, 'destroy']);
        Route::patch('mesas/{mesa}/toggle', [MesaController::class, 'toggleActiva']);
        Route::put('empresa/layout', [EmpresaController::class, 'updateLayout']);
        Route::put('empresa', [EmpresaController::class, 'update']);

        Route::get('platos', [PlatoController::class, 'index']);
        Route::post('platos', [PlatoController::class, 'store']);
        Route::get('platos/{plato}', [PlatoController::class, 'show']);
        Route::put('platos/{plato}', [PlatoController::class, 'update']);
        Route::delete('platos/{plato}', [PlatoController::class, 'destroy']);
        Route::patch('platos/{plato}/toggle', [PlatoController::class, 'toggleDisponible']);
        Route::put('platos/orden', [PlatoController::class, 'reordenar']);

        Route::get('cupones', [CuponController::class, 'index']);
        Route::post('cupones', [CuponController::class, 'store']);
        Route::put('cupones/{cupon}', [CuponController::class, 'update']);
        Route::patch('cupones/{cupon}/toggle', [CuponController::class, 'toggleActivo']);
        Route::delete('cupones/{cupon}', [CuponController::class, 'destroy']);

        Route::get('staff', [StaffController::class, 'index']);
        Route::post('staff', [StaffController::class, 'store']);
        Route::delete('staff/{user}', [StaffController::class, 'destroy']);
    });

    Route::post('demo/seed', function () {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => \Database\Seeders\DemoSeeder::class]);
        return response()->json(['message' => 'Datos demo cargados']);
    });
});
