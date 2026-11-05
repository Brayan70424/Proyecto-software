<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ProbarCorreo extends Command
{
    // Se pueden indicar uno o varios destinos separados por espacio
    protected $signature = 'correo:probar {destinos* : Una o más direcciones que recibirán el correo de prueba}';

    protected $description = 'Envía un correo de prueba a una o varias direcciones para comprobar la configuración SMTP';

    public function handle(): int
    {
        $mailer = config('mail.default');

        $this->line("Mailer: {$mailer} · Servidor: "
            . config('mail.mailers.smtp.host') . ':' . config('mail.mailers.smtp.port')
            . ' · Remitente: ' . config('mail.from.address'));

        if (in_array($mailer, ['log', 'array'], true)) {
            $this->warn("MAIL_MAILER={$mailer}: los correos NO se enviarán de verdad. Cambia a smtp en el .env.");
        }

        $enviados = 0;
        $fallidos = 0;

        foreach ($this->argument('destinos') as $destino) {
            if (! filter_var($destino, FILTER_VALIDATE_EMAIL)) {
                $this->error("✘ {$destino}: no es una dirección de correo válida.");
                $fallidos++;
                continue;
            }

            try {
                // Cada destinatario recibe su propio correo, igual que en la recuperación de contraseña
                Mail::raw(
                    'Si lees este mensaje, el envío de correos de tu proyecto funciona correctamente.',
                    function ($mensaje) use ($destino) {
                        $mensaje->to($destino)->subject('Prueba de correo - Autenticación');
                    }
                );
                $this->info("✔ Aceptado por el servidor: {$destino}");
                $enviados++;
            } catch (\Throwable $e) {
                $this->error("✘ {$destino}: " . $e->getMessage());
                $fallidos++;
            }
        }

        $this->line("Resultado: {$enviados} enviado(s), {$fallidos} con error. Revisa las bandejas y la carpeta de spam.");

        return $fallidos > 0 ? self::FAILURE : self::SUCCESS;
    }
}
