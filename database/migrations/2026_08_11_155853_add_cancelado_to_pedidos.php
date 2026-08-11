<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE pedidos MODIFY COLUMN estado ENUM('nuevo', 'preparacion', 'listo', 'entregado', 'cancelado') NOT NULL DEFAULT 'nuevo'");
        }
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE pedidos MODIFY COLUMN estado ENUM('nuevo', 'preparacion', 'listo', 'entregado') NOT NULL DEFAULT 'nuevo'");
        }
    }
};
