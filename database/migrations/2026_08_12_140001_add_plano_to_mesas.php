<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mesas', function (Blueprint $table) {
            $table->decimal('pos_x', 5, 1)->nullable()->after('activa');
            $table->decimal('pos_y', 5, 1)->nullable()->after('pos_x');
            $table->unsignedSmallInteger('rotacion')->default(0)->after('pos_y');
            $table->string('forma')->default('circular')->after('rotacion');
        });
    }

    public function down(): void
    {
        Schema::table('mesas', function (Blueprint $table) {
            $table->dropColumn(['pos_x', 'pos_y', 'rotacion', 'forma']);
        });
    }
};