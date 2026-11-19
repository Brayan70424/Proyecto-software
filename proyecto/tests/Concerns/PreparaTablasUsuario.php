<?php

namespace Tests\Concerns;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * El proyecto crea las tablas "usuario" y "password_reset_tokens" con el script SQL
 * (no con migraciones). Este trait las crea en la base de pruebas (SQLite en memoria).
 */
trait PreparaTablasUsuario
{
    protected function crearTablasDeUsuario(): void
    {
        if (!Schema::hasTable('usuario')) {
            Schema::create('usuario', function (Blueprint $table) {
                $table->id('id_usuario');
                $table->string('nombre');
                $table->string('correo')->unique();
                $table->string('password');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }
    }
}
