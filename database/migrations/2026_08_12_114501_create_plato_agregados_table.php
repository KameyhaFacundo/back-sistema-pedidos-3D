<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plato_agregados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plato_id')->constrained('platos')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->decimal('precio', 10, 2);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plato_agregados');
    }
};
