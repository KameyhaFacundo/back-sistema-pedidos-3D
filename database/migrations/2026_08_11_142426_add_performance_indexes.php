<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->index(['estado', 'created_at']);
        });

        Schema::table('platos', function (Blueprint $table) {
            $table->index('disponible');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropIndex(['estado', 'created_at']);
        });

        Schema::table('platos', function (Blueprint $table) {
            $table->dropIndex(['disponible']);
        });
    }
};
