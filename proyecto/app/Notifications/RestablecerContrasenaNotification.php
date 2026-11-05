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
        // CA-03.3: enlace con el token seguro y de un solo uso
        $url = route('password.reset', [
            'token' => $this->token,
            'correo' => $notifiable->getEmailForPasswordReset(),
        ]);

        // CA-03.4: se informa la vigencia configurada en config/auth.php (30 minutos)
        $minutos = config('auth.passwords.users.expire');

        // Solo en desarrollo (MAIL_MAILER=log): deja el enlace legible en storage/logs/laravel.log
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
