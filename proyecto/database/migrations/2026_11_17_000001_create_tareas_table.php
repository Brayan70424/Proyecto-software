<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->string('nombre', 80);
            $table->text('descripcion')->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->enum('estado', ['Pendiente', 'En progreso', 'Completada'])->default('Pendiente');
            $table->timestamps();
            $table->foreign('usuario_id')->references('id_usuario')->on('usuario')->cascadeOnDelete()->cascadeOnUpdate();
            $table->index('usuario_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};
