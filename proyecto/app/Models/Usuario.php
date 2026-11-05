<?php

namespace App\Models;

use App\Notifications\RestablecerContrasenaNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable; // permite enviar correos a este usuario (HU-03)

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

    // ---- HU-03: Laravel asume que el correo está en la columna "email"; la nuestra es "correo" ----

    // Valor que el sistema de recuperación guarda y busca en password_reset_tokens
    public function getEmailForPasswordReset(): string
    {
        return $this->correo;
    }

    // Dirección a la que se envían los correos de este usuario
    public function routeNotificationForMail(): string
    {
        return $this->correo;
    }

    // Correo con el enlace de recuperación (en español y con nuestra ruta)
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new RestablecerContrasenaNotification($token));
    }
}
