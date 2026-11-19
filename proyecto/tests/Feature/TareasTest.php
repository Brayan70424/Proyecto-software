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

    private function datosValidos(): array
    {
        return ['nombre' => 'Tarea', 'descripcion' => 'Detalle', 'fecha_inicio' => '2026-11-18', 'fecha_fin' => '2026-11-20', 'estado' => 'Pendiente'];
    }

    public function test_consulta_los_datos_completos_de_una_tarea(): void
    {
        $this->usuarioAutenticado();
        $id = $this->postJson('/api/tareas', $this->datosValidos())->json('id');

        $this->getJson('/api/tareas/'.$id)->assertOk()
            ->assertJsonPath('nombre', 'Tarea')
            ->assertJsonPath('descripcion', 'Detalle')
            ->assertJsonPath('fecha_inicio', '2026-11-18')
            ->assertJsonPath('fecha_fin', '2026-11-20')
            ->assertJsonPath('estado', 'Pendiente');
    }

    public function test_tarea_inexistente_responde_404(): void
    {
        $this->usuarioAutenticado();

        $this->getJson('/api/tareas/9999')->assertNotFound();
        $this->putJson('/api/tareas/9999', $this->datosValidos())->assertNotFound();
        $this->deleteJson('/api/tareas/9999')->assertNotFound();
    }

    public function test_no_autenticado_no_puede_ver_editar_ni_eliminar(): void
    {
        $this->getJson('/api/tareas/1')->assertUnauthorized();
        $this->putJson('/api/tareas/1', $this->datosValidos())->assertUnauthorized();
        $this->deleteJson('/api/tareas/1')->assertUnauthorized();
    }

    public function test_usuario_no_puede_tocar_tareas_de_otro_usuario(): void
    {
        $this->usuarioAutenticado();
        $id = $this->postJson('/api/tareas', $this->datosValidos())->json('id');

        $otro = Usuario::create(['nombre' => 'Otro', 'correo' => 'otro@test.com', 'password' => 'password123']);
        $this->actingAs($otro);

        $this->getJson('/api/tareas')->assertOk()->assertJsonCount(0);
        $this->getJson('/api/tareas/'.$id)->assertNotFound();
        $this->putJson('/api/tareas/'.$id, $this->datosValidos())->assertNotFound();
        $this->deleteJson('/api/tareas/'.$id)->assertNotFound();
        $this->assertDatabaseHas('tareas', ['id' => $id]);
    }

    public function test_acepta_fecha_fin_igual_a_fecha_inicio(): void
    {
        $this->usuarioAutenticado();
        $datos = $this->datosValidos();
        $datos['fecha_fin'] = $datos['fecha_inicio'];

        $this->postJson('/api/tareas', $datos)->assertCreated();
    }

}
