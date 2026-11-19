<?php

namespace Tests\Unit;

use App\Models\Tarea;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\PreparaTablasUsuario;
use Tests\TestCase;

/**
 * Pruebas unitarias del modelo Tarea.
 */
class TareaModelTest extends TestCase
{
    use RefreshDatabase;
    use PreparaTablasUsuario;

    protected function setUp(): void
    {
        parent::setUp();
        $this->crearTablasDeUsuario();
    }

    private function crearTarea(array $cambios = []): Tarea
    {
        $usuario = Usuario::create(['nombre' => 'Ana', 'correo' => 'ana@test.com', 'password' => 'Abcd1234']);

        return Tarea::create(array_merge([
            'usuario_id' => $usuario->id_usuario,
            'nombre' => 'Entrega',
            'descripcion' => 'Detalle',
            'fecha_inicio' => '2026-11-18',
            'fecha_fin' => '2026-11-20',
            'estado' => 'En progreso',
        ], $cambios));
    }

    public function test_guarda_los_cinco_campos_de_la_tarea(): void
    {
        $tarea = $this->crearTarea()->fresh();

        $this->assertSame('Entrega', $tarea->nombre);
        $this->assertSame('Detalle', $tarea->descripcion);
        $this->assertSame('2026-11-18', $tarea->fecha_inicio->format('Y-m-d'));
        $this->assertSame('2026-11-20', $tarea->fecha_fin->format('Y-m-d'));
        $this->assertSame('En progreso', $tarea->estado);
    }

}
