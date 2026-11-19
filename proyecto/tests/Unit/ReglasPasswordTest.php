<?php

namespace Tests\Unit;

use App\Support\ReglasPassword;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

/**
 * Prueba UNITARIA de las reglas de contraseña de la HU-01
 * (mínimo 8 caracteres, mayúscula, minúscula, número y confirmación).
 */
class ReglasPasswordTest extends TestCase
{
    private function pasaValidacion(string $password, ?string $confirmacion = null): bool
    {
        $datos = ['password' => $password, 'password_confirmation' => $confirmacion ?? $password];

        return Validator::make($datos, ['password' => ReglasPassword::rules()])->passes();
    }

    public function test_limite_inferior_de_8_caracteres(): void
    {
        $this->assertFalse($this->pasaValidacion('Abcd123'));   // 7 caracteres
        $this->assertTrue($this->pasaValidacion('Abcd1234'));   // 8 caracteres
        $this->assertTrue($this->pasaValidacion('Abcd12345'));  // 9 caracteres
    }

    public function test_rechaza_contrasena_sin_mayuscula(): void
    {
        $this->assertFalse($this->pasaValidacion('abcd1234'));
    }

}
