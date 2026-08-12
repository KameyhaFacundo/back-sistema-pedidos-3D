<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('nombre');
            $table->string('whatsapp')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->after('id')->constrained('empresas')->nullOnDelete();
        });

        Schema::table('platos', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->after('id')->constrained('empresas')->cascadeOnDelete();
        });

        Schema::table('pedidos', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->after('id')->constrained('empresas')->cascadeOnDelete();
        });

        Schema::table('mesas', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->after('id')->constrained('empresas')->cascadeOnDelete();
        });

        Schema::table('llamados', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->after('id')->constrained('empresas')->cascadeOnDelete();
        });

        Schema::table('cupones', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->after('id')->constrained('empresas')->cascadeOnDelete();
        });

        Schema::table('ar_vistas', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->after('id')->constrained('empresas')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ar_vistas', fn (Blueprint $t) => $t->dropConstrainedForeignId('empresa_id'));
        Schema::table('cupones', fn (Blueprint $t) => $t->dropConstrainedForeignId('empresa_id'));
        Schema::table('llamados', fn (Blueprint $t) => $t->dropConstrainedForeignId('empresa_id'));
        Schema::table('mesas', fn (Blueprint $t) => $t->dropConstrainedForeignId('empresa_id'));
        Schema::table('pedidos', fn (Blueprint $t) => $t->dropConstrainedForeignId('empresa_id'));
        Schema::table('platos', fn (Blueprint $t) => $t->dropConstrainedForeignId('empresa_id'));
        Schema::table('users', fn (Blueprint $t) => $t->dropConstrainedForeignId('empresa_id'));
        Schema::dropIfExists('empresas');
    }
};
