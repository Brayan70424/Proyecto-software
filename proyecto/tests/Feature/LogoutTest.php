<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\PreparaTablasUsuario;
use Tests\TestCase;

/**
 * HU-04: cierre de sesión (LogoutController).
 */
class LogoutTest extends TestCase
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

    public function test_cerrar_sesion_redirige_al_login_con_mensaje(): void
    {
        $this->actingAs($this->usuario())->post('/logout')
            ->assertRedirect('/login')
            ->assertSessionHas('exito', 'Sesión cerrada correctamente.');

        $this->assertGuest();
    }

    public function test_despues_de_cerrar_sesion_no_se_accede_a_paginas_privadas(): void
    {
        $this->actingAs($this->usuario())->post('/logout');

        $this->get('/panel')->assertRedirect('/login');
        $this->get('/tareas')->assertRedirect('/login');
        $this->get('/calendario')->assertRedirect('/login');
    }

}
