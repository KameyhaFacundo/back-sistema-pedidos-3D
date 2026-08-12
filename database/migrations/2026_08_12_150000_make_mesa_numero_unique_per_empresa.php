<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mesas', function (Blueprint $table) {
            $table->dropUnique('mesas_numero_unique');
            $table->unique(['empresa_id', 'numero']);
        });
    }

    public function down(): void
    {
        Schema::table('mesas', function (Blueprint $table) {
            $table->dropUnique('mesas_empresa_id_numero_unique');
            $table->unique('numero');
        });
    }
};