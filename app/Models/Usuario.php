<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    // La tabla ya existe en la BD con este nombre (singular).
    protected $table = 'usuario';

    // La llave primaria se llama id_usuario (por defecto Laravel esperaría 'id')
    protected $primaryKey = 'id_usuario';

    // Si la tabla NO tiene created_at / updated_at, descomenta la siguiente línea:
    // public $timestamps = false;

    // Campos que se pueden asignar masivamente con Usuario::create([...])
    protected $fillable = ['nombre', 'correo', 'password'];

    // Nunca se devuelve el hash en arrays o JSON
    protected $hidden = ['password'];

    // 'hashed' aplica Hash::make() automáticamente al asignar el password (CA-01.7)
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
