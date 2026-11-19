<?php

namespace Tests\Feature;

use App\Models\Usuario;
use App\Notifications\RestablecerContrasenaNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\Concerns\PreparaTablasUsuario;
use Tests\TestCase;

/**
 * HU-03: recuperación de contraseña (RecuperarContrasenaController).
 */
class RecuperarContrasenaTest extends TestCase
{
    use RefreshDatabase;
    use PreparaTablasUsuario;

    private const MENSAJE_GENERICO = 'Si el correo está registrado, recibirás un enlace para restablecer tu contraseña.';

    protected function setUp(): void
    {
        parent::setUp();
        $this->crearTablasDeUsuario();
    }

    private function usuario(): Usuario
    {
        return Usuario::create(['nombre' => 'Ana', 'correo' => 'ana@test.com', 'password' => 'Abcd1234']);
    }

    private function datosDeRestablecer(string $token, array $cambios = []): array
    {
        return array_merge([
            'token' => $token,
            'correo' => 'ana@test.com',
            'password' => 'Nueva1234',
            'password_confirmation' => 'Nueva1234',
        ], $cambios);
    }

    public function test_envia_el_enlace_a_un_correo_registrado(): void
    {
        Notification::fake();
        $usuario = $this->usuario();

        $this->post('/olvide-contrasena', ['correo' => 'ANA@test.com'])
            ->assertSessionHas('exito', self::MENSAJE_GENERICO);

        Notification::assertSentTo($usuario, RestablecerContrasenaNotification::class);
    }

    public function test_un_correo_no_registrado_recibe_el_mismo_mensaje_y_no_se_envia_nada(): void
    {
        Notification::fake();

        $this->post('/olvide-contrasena', ['correo' => 'nadie@test.com'])
            ->assertSessionHas('exito', self::MENSAJE_GENERICO);

        Notification::assertNothingSent();
    }

    public function test_cambia_la_contrasena_con_un_token_valido(): void
    {
        $usuario = $this->usuario();
        $token = Password::createToken($usuario);

        $this->post('/restablecer-contrasena', $this->datosDeRestablecer($token))
            ->assertRedirect('/login')
            ->assertSessionHas('exito', 'Tu contraseña fue actualizada. Ya puedes iniciar sesión.');

        $this->assertTrue(Hash::check('Nueva1234', $usuario->fresh()->password));
    }

    public function test_rechaza_un_token_expirado_despues_de_30_minutos(): void
    {
        $usuario = $this->usuario();
        $token = Password::createToken($usuario);

        $this->travel(31)->minutes();

        $this->post('/restablecer-contrasena', $this->datosDeRestablecer($token))
            ->assertSessionHasErrors(['correo' => 'El enlace de recuperación no es válido o ha expirado. Solicita uno nuevo.']);
        $this->assertTrue(Hash::check('Abcd1234', $usuario->fresh()->password));
    }

}
