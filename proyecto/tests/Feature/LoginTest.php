<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\PreparaTablasUsuario;
use Tests\TestCase;

/**
 * HU-02: inicio de sesión y HU-05: límite de intentos fallidos (LoginController).
 */
class LoginTest extends TestCase
{
    use RefreshDatabase;
    use PreparaTablasUsuario;

    private const MENSAJE_GENERICO = 'Las credenciales ingresadas no son correctas.';

    protected function setUp(): void
    {
        parent::setUp();
        $this->crearTablasDeUsuario();
    }

    private function crearUsuario(): Usuario
    {
        return Usuario::create(['nombre' => 'Ana', 'correo' => 'ana@test.com', 'password' => 'Abcd1234']);
    }

    private function iniciarSesion(string $correo, string $password)
    {
        return $this->post('/login', ['correo' => $correo, 'password' => $password]);
    }

    public function test_el_formulario_de_login_solicita_correo_y_contrasena(): void
    {
        $this->get('/login')->assertOk()
            ->assertSee('name="correo"', false)
            ->assertSee('name="password"', false);
    }

    public function test_inicia_sesion_con_credenciales_validas(): void
    {
        $usuario = $this->crearUsuario();

        $this->iniciarSesion('ana@test.com', 'Abcd1234')->assertRedirect('/panel');

        $this->assertAuthenticatedAs($usuario);
    }

    public function test_rechaza_una_contrasena_incorrecta_con_mensaje_generico(): void
    {
        $this->crearUsuario();

        $this->iniciarSesion('ana@test.com', 'Incorrecta1')
            ->assertSessionHasErrors(['credenciales' => self::MENSAJE_GENERICO]);

        $this->assertGuest();
    }

    public function test_los_campos_vacios_son_obligatorios(): void
    {
        $this->post('/login', [])->assertSessionHasErrors([
            'correo' => 'El correo electrónico es obligatorio.',
            'password' => 'La contraseña es obligatoria.',
        ]);
    }

    public function test_bloquea_despues_de_cinco_intentos_fallidos(): void
    {
        $this->crearUsuario();

        // Intentos 1 a 4: mensaje genérico.
        for ($i = 1; $i <= 4; $i++) {
            $this->iniciarSesion('ana@test.com', 'Incorrecta1')
                ->assertSessionHasErrors(['credenciales' => self::MENSAJE_GENERICO]);
        }

        // Intento 5: se activa el bloqueo de 15 minutos.
        $this->iniciarSesion('ana@test.com', 'Incorrecta1')
            ->assertSessionHasErrors(['credenciales' => 'Has superado el número de intentos permitidos. Intenta nuevamente en 15 minutos.']);

        // Aunque ahora use la contraseña correcta, sigue bloqueado.
        $this->iniciarSesion('ana@test.com', 'Abcd1234')
            ->assertSessionHasErrors('credenciales');
        $this->assertGuest();
    }

}
