<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    // ---- Reglas de la HU-05 (límite de intentos fallidos) ----
    private const MAX_INTENTOS = 5;          // CA-05.1: máximo 5 intentos fallidos
    private const VENTANA_SEGUNDOS = 900;    // CA-05.1: ventana de 15 minutos
    private const BLOQUEO_SEGUNDOS = 900;    // CA-05.2: bloqueo de 15 minutos

    // GET /login -> muestra el formulario
    public function showLoginForm()
    {
        return view('login');
    }

    // POST /login -> valida, aplica el límite de intentos y autentica
    public function authenticate(Request $request)
    {
        // HU-02 · CA-02.1 / CA-02.4: ambos campos son obligatorios
        // (los envíos vacíos no cuentan como intento fallido porque se detienen aquí)
        $datos = $request->validate([
            'correo' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'correo.required' => 'El correo electrónico es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        // El correo se guardó en minúsculas al registrarse, se normaliza igual aquí
        $correo = Str::lower(trim($datos['correo']));

        // HU-05 · CA-05.1: el contador es por combinación correo + IP
        $claveIntentos = $this->claveIntentos($correo, $request->ip());
        $claveBloqueo = $claveIntentos . ':bloqueo';

        // HU-05 · CA-05.2: si hay un bloqueo vigente, no se intenta autenticar
        if (RateLimiter::tooManyAttempts($claveBloqueo, 1)) {
            return $this->respuestaBloqueado(RateLimiter::availableIn($claveBloqueo));
        }

        // HU-02 · CA-02.2: Auth::attempt busca por correo y compara el password contra el hash
        if (Auth::attempt(['correo' => $correo, 'password' => $datos['password']])) {
            // HU-05 · CA-05.5: el inicio de sesión exitoso reinicia el contador
            RateLimiter::clear($claveIntentos);

            // Evita fijación de sesión
            $request->session()->regenerate();

            // HU-02 · CA-02.5: redirige al área privada (o a la URL privada que intentó abrir)
            return redirect()->intended(route('panel'));
        }

        // Falló: se suma un intento dentro de la ventana de 15 minutos
        RateLimiter::hit($claveIntentos, self::VENTANA_SEGUNDOS);

        // HU-05 · CA-05.1 / CA-05.2: al llegar al 5.º fallo se activa el bloqueo de 15 minutos
        if (RateLimiter::attempts($claveIntentos) >= self::MAX_INTENTOS) {
            RateLimiter::hit($claveBloqueo, self::BLOQUEO_SEGUNDOS);
            RateLimiter::clear($claveIntentos);

            return $this->respuestaBloqueado(self::BLOQUEO_SEGUNDOS);
        }

        // HU-02 · CA-02.3: mensaje genérico, no revela si falló el correo o la contraseña
        return back()
            ->withErrors(['credenciales' => 'Las credenciales ingresadas no son correctas.'])
            ->onlyInput('correo');
    }

    // Clave del contador: hash de correo + IP (no se guarda el correo en claro)
    private function claveIntentos(string $correo, ?string $ip): string
    {
        return 'login:' . sha1($correo . '|' . $ip);
    }

    // HU-05 · CA-05.3: mensaje comprensible, sin revelar si la cuenta existe
    private function respuestaBloqueado(int $segundos): RedirectResponse
    {
        $minutos = max(1, (int) ceil($segundos / 60));
        $unidad = $minutos === 1 ? 'minuto' : 'minutos';

        return back()
            ->withErrors([
                'credenciales' => "Has superado el número de intentos permitidos. Intenta nuevamente en {$minutos} {$unidad}.",
            ])
            ->onlyInput('correo');
    }
}
