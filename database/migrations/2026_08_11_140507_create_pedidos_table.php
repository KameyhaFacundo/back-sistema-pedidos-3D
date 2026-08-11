<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['mesa', 'retiro']);
            $table->foreignId('mesa_id')->nullable()->constrained('mesas')->nullOnDelete();
            $table->enum('estado', ['nuevo', 'preparacion', 'listo', 'entregado'])->default('nuevo');
            $table->enum('medio_pago', ['efectivo', 'transferencia']);
            $table->enum('estado_pago', ['pendiente', 'pagado'])->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
