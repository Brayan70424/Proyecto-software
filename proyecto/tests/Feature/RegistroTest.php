<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\PreparaTablasUsuario;
use Tests\TestCase;

/**
 * HU-01: registro de usuarios (RegistroController + RegistroRequest).
 */
class RegistroTest extends TestCase
{
    use RefreshDatabase;
    use PreparaTablasUsuario;

    protected function setUp(): void
    {
        parent::setUp();
        $this->crearTablasDeUsuario();
    }

    private function datosValidos(array $cambios = []): array
    {
        return array_merge([
            'nombre' => 'Cheril',
            'correo' => 'cheril@xampp.com',
            'password' => 'Abcd1234',
            'password_confirmation' => 'Abcd1234',
        ], $cambios);
    }

    public function test_el_formulario_de_registro_solicita_los_cuatro_campos(): void
    {
        $this->get('/registro')->assertOk()
            ->assertSee('name="nombre"', false)
            ->assertSee('name="correo"', false)
            ->assertSee('name="password"', false)
            ->assertSee('name="password_confirmation"', false);
    }

    public function test_registra_un_usuario_con_datos_validos(): void
    {
        $this->post('/registro', $this->datosValidos())
            ->assertRedirect('/login')
            ->assertSessionHas('exito', 'Cuenta creada correctamente. Ya puedes iniciar sesión.');

        $this->assertDatabaseHas('usuario', ['correo' => 'cheril@xampp.com', 'nombre' => 'Cheril']);
    }

    public function test_todos_los_campos_son_obligatorios(): void
    {
        $this->post('/registro', [])
            ->assertSessionHasErrors(['nombre', 'correo', 'password', 'password_confirmation']);

        $this->assertDatabaseCount('usuario', 0);
    }

    public function test_rechaza_un_correo_ya_registrado_sin_importar_mayusculas(): void
    {
        $this->post('/registro', $this->datosValidos());

        $this->post('/registro', $this->datosValidos(['correo' => 'CHERIL@xampp.com']))
            ->assertSessionHasErrors(['correo' => 'Este correo electrónico ya está registrado.']);

        $this->assertDatabaseCount('usuario', 1);
    }

}
