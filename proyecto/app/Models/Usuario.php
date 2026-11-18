<?php

namespace App\Models;

use App\Notifications\RestablecerContrasenaNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Usuario extends Authenticatable
{
    use Notifiable; 

    
    protected $table = 'usuario';

    
    protected $primaryKey = 'id_usuario';

    
    

    
    protected $fillable = ['nombre', 'correo', 'password'];

    
    protected $hidden = ['password'];

    public function tareas(): HasMany
    {
        return $this->hasMany(Tarea::class, 'usuario_id', 'id_usuario');
    }

    
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    

    
    public function getEmailForPasswordReset(): string
    {
        return $this->correo;
    }

    
    public function routeNotificationForMail(): string
    {
        return $this->correo;
    }

    
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new RestablecerContrasenaNotification($token));
    }
}
