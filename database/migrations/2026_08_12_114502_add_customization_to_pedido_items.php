<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedido_items', function (Blueprint $table) {
            $table->string('presentacion_nombre')->nullable()->after('cantidad');
            $table->json('agregados')->nullable()->after('presentacion_nombre');
            $table->string('observacion')->nullable()->after('agregados');
        });
    }

    public function down(): void
    {
        Schema::table('pedido_items', function (Blueprint $table) {
            $table->dropColumn(['presentacion_nombre', 'agregados', 'observacion']);
        });
    }
};
