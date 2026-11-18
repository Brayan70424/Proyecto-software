<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;

class UsuarioPruebaSeeder extends Seeder
{
    
    public function run(): void
    {
        Usuario::firstOrCreate(
            ['correo' => 'ana@test.local'],
            ['nombre' => 'Ana Prueba', 'password' => 'Abcd1234']
        );
    }
}
