<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('token', 40)->nullable()->unique()->after('id');
        });

        // Backfill existing rows so the unique index has no null collisions
        // once we drop nullability... left nullable since old orders have
        // no legitimate anonymous owner to hand a token to anyway.
        \App\Models\Pedido::whereNull('token')->each(function ($pedido) {
            $pedido->update(['token' => Str::random(40)]);
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn('token');
        });
    }
};
