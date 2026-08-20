<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->boolean('abierto')->default(true)->after('activo');
            $table->unsignedInteger('tiempo_estimado')->nullable()->after('abierto');
        });
    }

    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn(['abierto', 'tiempo_estimado']);
        });
    }
};