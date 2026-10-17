<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    // La raíz redirige al login cuando no hay sesión
    public function test_la_raiz_redirige_al_login(): void
    {
        $this->get('/')->assertRedirect(route('login'));
    }
}
