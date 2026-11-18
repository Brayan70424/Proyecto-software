<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class RestablecerContrasenaNotification extends Notification
{
    public function __construct(private string $token)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        
        $url = route('password.reset', [
            'token' => $this->token,
            'correo' => $notifiable->getEmailForPasswordReset(),
        ]);

        
        $minutos = config('auth.passwords.users.expire');

        
        if (config('mail.default') === 'log') {
            Log::info("Enlace de recuperación para {$notifiable->getEmailForPasswordReset()}: {$url}");
        }

        return (new MailMessage)
            ->subject('Restablecer tu contraseña')
            ->greeting('Hola, ' . $notifiable->nombre)
            ->line('Recibimos una solicitud para restablecer la contraseña de tu cuenta.')
            ->action('Restablecer contraseña', $url)
            ->line("Este enlace expira en {$minutos} minutos y solo puede usarse una vez.")
            ->line('Si no solicitaste este cambio, puedes ignorar este mensaje.');
    }
}
