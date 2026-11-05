<?php

namespace App\Http\Controllers;

use App\Http\Requests\RestablecerContrasenaRequest;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class RecuperarContrasenaController extends Controller
{
    // GET /olvide-contrasena -> formulario para pedir el enlace (CA-03.1)
    public function formularioSolicitud()
    {
        return view('recuperar');
    }

    // POST /olvide-contrasena -> genera y envía el enlace si la cuenta existe
    public function enviarEnlace(Request $request)
    {
        $request->merge(['correo' => Str::lower(trim((string) $request->input('correo')))]);

        // CA-03.1: se solicita mediante un correo electrónico
        $request->validate([
            'correo' => ['required', 'string', 'email'],
        ], [
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El correo electrónico no tiene un formato válido.',
        ]);

        // CA-03.3: si la cuenta existe, Laravel crea el token (guardado con hash en
        // password_reset_tokens) y envía el correo. Si no existe, no hace nada.
        // El resultado se ignora a propósito para no revelar si el correo está registrado.
        try {
            Password::sendResetLink(['correo' => $request->input('correo')]);
        } catch (\Throwable $e) {
            // Si el servidor de correo falla, se registra el motivo en storage/logs/laravel.log.
            // En desarrollo (APP_DEBUG=true) se muestra el error para poder corregir el .env.
            // En producción se responde igual que siempre, para no revelar si la cuenta existe.
            Log::error('No se pudo enviar el correo de recuperación: ' . $e->getMessage());

            if (config('app.debug')) {
                throw $e;
            }
        }

        // CA-03.2: mensaje genérico, igual exista o no la cuenta
        return back()->with(
            'exito',
            'Si el correo está registrado, recibirás un enlace para restablecer tu contraseña.'
        );
    }

    // GET /restablecer-contrasena/{token} -> formulario de nueva contraseña
    public function formularioRestablecer(Request $request, string $token)
    {
        return view('restablecer', [
            'token' => $token,
            'correo' => $request->query('correo'),
        ]);
    }

    // POST /restablecer-contrasena -> valida el token y cambia la contraseña
    public function restablecer(RestablecerContrasenaRequest $request)
    {
        // Password::reset comprueba que el token coincida con el guardado (hash) y que no
        // hayan pasado más de 30 minutos (CA-03.4). Solo si todo es válido ejecuta la función.
        $estado = Password::reset(
            $request->safe()->only(['correo', 'password', 'password_confirmation', 'token']),
            function (Usuario $usuario, string $password) {
                // CA-03.7: se reemplaza la contraseña; la anterior ya no sirve para iniciar sesión
                // (el cast 'hashed' del modelo la guarda con bcrypt)
                $usuario->forceFill(['password' => $password])->save();
            }
        );

        if ($estado === Password::PASSWORD_RESET) {
            // CA-03.6: al cambiarla, Laravel ya eliminó el token de password_reset_tokens
            return redirect()
                ->route('login')
                ->with('exito', 'Tu contraseña fue actualizada. Ya puedes iniciar sesión.');
        }

        // Token inválido, usado, expirado o correo que no corresponde: un solo mensaje
        return back()
            ->withInput($request->only('correo'))
            ->withErrors(['correo' => 'El enlace de recuperación no es válido o ha expirado. Solicita uno nuevo.']);
    }
}
