<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;

class UsuarioPruebaSeeder extends Seeder
{
    // Crea el usuario de precondición del plan de pruebas (ana@test.local / Abcd1234)
    public function run(): void
    {
        Usuario::firstOrCreate(
            ['correo' => 'ana@test.local'],
            ['nombre' => 'Ana Prueba', 'password' => 'Abcd1234']
        );
    }
}
