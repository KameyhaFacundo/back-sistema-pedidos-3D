<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cupones', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->decimal('descuento', 10, 2)->comment('Monto fijo o porcentaje según tipo');
            $table->enum('tipo', ['fijo', 'porcentaje'])->default('fijo');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('direccion')->nullable()->after('celular');
            $table->decimal('descuento', 10, 2)->default(0)->after('direccion');
            $table->foreignId('cupon_id')->nullable()->after('descuento')->constrained('cupones')->nullOnDelete();
        });

        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE pedidos MODIFY COLUMN tipo ENUM('mesa', 'retiro', 'envio') NOT NULL");
        }
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE pedidos MODIFY COLUMN tipo ENUM('mesa', 'retiro') NOT NULL");
        }

        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cupon_id');
            $table->dropColumn(['direccion', 'descuento']);
        });

        Schema::dropIfExists('cupones');
    }
};
