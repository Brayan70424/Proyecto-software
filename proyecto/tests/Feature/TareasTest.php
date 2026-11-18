<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class TareasTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (!Schema::hasTable('usuario')) {
            Schema::create('usuario', function (Blueprint $table) {
                $table->id('id_usuario');
                $table->string('nombre');
                $table->string('correo')->unique();
                $table->string('password');
                $table->timestamps();
            });
        }
    }

    private function usuarioAutenticado(): Usuario
    {
        $usuario = Usuario::create(['nombre' => 'Estudiante', 'correo' => 'estudiante@test.com', 'password' => 'password123']);
        $this->actingAs($usuario);
        return $usuario;
    }

    public function test_usuario_autenticado_puede_crear_consultar_editar_y_eliminar_tarea(): void
    {
        $this->usuarioAutenticado();
        $datos = ['nombre' => 'Entrega del proyecto', 'descripcion' => 'Terminar Sprint 3', 'fecha_inicio' => '2026-11-18', 'fecha_fin' => '2026-11-20', 'estado' => 'Pendiente'];

        $this->postJson('/api/tareas', $datos)->assertCreated()->assertJsonPath('nombre', $datos['nombre']);
        $id = \App\Models\Tarea::firstOrFail()->id;
        $this->getJson('/api/tareas')->assertOk()->assertJsonCount(1);
        $this->putJson('/api/tareas/'.$id, [...$datos, 'estado' => 'Completada'])->assertOk()->assertJsonPath('estado', 'Completada');
        $this->deleteJson('/api/tareas/'.$id)->assertOk();
        $this->assertDatabaseMissing('tareas', ['id' => $id]);
    }

    public function test_rechaza_nombre_vacio_y_fechas_invertidas(): void
    {
        $this->usuarioAutenticado();
        $datos = ['nombre' => '', 'descripcion' => '', 'fecha_inicio' => '2026-11-20', 'fecha_fin' => '2026-11-18', 'estado' => 'Pendiente'];
        $this->postJson('/api/tareas', $datos)->assertUnprocessable()->assertJsonValidationErrors(['nombre', 'fecha_fin']);
    }

    public function test_no_autenticado_no_puede_consultar_ni_crear_tareas(): void
    {
        $this->getJson('/api/tareas')->assertUnauthorized();
        $this->postJson('/api/tareas', [])->assertUnauthorized();
    }

    public function test_no_acepta_estados_no_permitidos(): void
    {
        $this->usuarioAutenticado();
        $datos = ['nombre' => 'Tarea', 'fecha_inicio' => '2026-11-18', 'fecha_fin' => '2026-11-18', 'estado' => 'Cancelada'];
        $this->postJson('/api/tareas', $datos)->assertUnprocessable()->assertJsonValidationErrors('estado');
    }
}
