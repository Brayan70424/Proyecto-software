<?php

namespace Tests\Unit;

use App\Models\Tarea;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Concerns\PreparaTablasUsuario;
use Tests\TestCase;

/**
 * Pruebas unitarias del modelo Usuario.
 */
class UsuarioTest extends TestCase
{
    use RefreshDatabase;
    use PreparaTablasUsuario;

    protected function setUp(): void
    {
        parent::setUp();
        $this->crearTablasDeUsuario();
    }

    private function crearUsuario(): Usuario
    {
        return Usuario::create(['nombre' => 'Ana', 'correo' => 'ana@test.com', 'password' => 'Abcd1234']);
    }

    public function test_la_contrasena_se_guarda_con_hash(): void
    {
        $usuario = $this->crearUsuario();

        $this->assertNotSame('Abcd1234', $usuario->password);
        $this->assertTrue(Hash::check('Abcd1234', $usuario->password));
    }

    public function test_el_correo_se_usa_para_recuperar_la_contrasena(): void
    {
        $usuario = $this->crearUsuario();

        $this->assertSame('ana@test.com', $usuario->getEmailForPasswordReset());
        $this->assertSame('ana@test.com', $usuario->routeNotificationForMail());
    }

    public function test_un_usuario_tiene_muchas_tareas(): void
    {
        $usuario = $this->crearUsuario();
        Tarea::create(['usuario_id' => $usuario->id_usuario, 'nombre' => 'Tarea 1', 'fecha_inicio' => '2026-11-18', 'fecha_fin' => '2026-11-19', 'estado' => 'Pendiente']);

        $this->assertInstanceOf(HasMany::class, $usuario->tareas());
        $this->assertCount(1, $usuario->tareas);
        $this->assertSame('Tarea 1', $usuario->tareas->first()->nombre);
    }
}
