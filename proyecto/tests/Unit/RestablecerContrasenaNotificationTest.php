<?php

namespace Tests\Unit;

use App\Models\Usuario;
use App\Notifications\RestablecerContrasenaNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\PreparaTablasUsuario;
use Tests\TestCase;

/**
 * Pruebas unitarias de la notificación de recuperación de contraseña (HU-03).
 */
class RestablecerContrasenaNotificationTest extends TestCase
{
    use RefreshDatabase;
    use PreparaTablasUsuario;

    protected function setUp(): void
    {
        parent::setUp();
        $this->crearTablasDeUsuario();
    }

    private function usuario(): Usuario
    {
        return Usuario::create(['nombre' => 'Ana', 'correo' => 'ana@test.com', 'password' => 'Abcd1234']);
    }

    public function test_el_correo_trae_el_enlace_con_token_y_correo_del_usuario(): void
    {
        $mensaje = (new RestablecerContrasenaNotification('token-de-prueba'))->toMail($this->usuario());

        $this->assertSame('Restablecer tu contraseña', $mensaje->subject);
        $this->assertSame('Hola, Ana', $mensaje->greeting);
        $this->assertStringContainsString('/restablecer-contrasena/token-de-prueba', $mensaje->actionUrl);
        $this->assertStringContainsString('ana%40test.com', $mensaje->actionUrl);
    }

}
