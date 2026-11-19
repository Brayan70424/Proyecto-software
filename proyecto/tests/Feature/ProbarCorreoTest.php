<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Comando artisan correo:probar (ProbarCorreo).
 * En las pruebas MAIL_MAILER=array, así que no se envía ningún correo real.
 */
class ProbarCorreoTest extends TestCase
{
    public function test_envia_el_correo_de_prueba_a_una_direccion_valida(): void
    {
        $this->artisan('correo:probar', ['destinos' => ['ana@test.com']])
            ->expectsOutputToContain('Aceptado por el servidor: ana@test.com')
            ->expectsOutputToContain('1 enviado(s), 0 con error')
            ->assertExitCode(0);
    }

}
