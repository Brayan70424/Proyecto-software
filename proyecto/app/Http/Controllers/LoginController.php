<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    
    private const MAX_INTENTOS = 5;          
    private const VENTANA_SEGUNDOS = 900;    
    private const BLOQUEO_SEGUNDOS = 900;    

    
    public function showLoginForm()
    {
        return view('login');
    }

    
    public function authenticate(Request $request)
    {
        
        
        $datos = $request->validate([
            'correo' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'correo.required' => 'El correo electrónico es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        
        $correo = Str::lower(trim($datos['correo']));

        
        $claveIntentos = $this->claveIntentos($correo, $request->ip());
        $claveBloqueo = $claveIntentos . ':bloqueo';

        
        if (RateLimiter::tooManyAttempts($claveBloqueo, 1)) {
            return $this->respuestaBloqueado(RateLimiter::availableIn($claveBloqueo));
        }

        
        if (Auth::attempt(['correo' => $correo, 'password' => $datos['password']])) {
            
            RateLimiter::clear($claveIntentos);

            
            $request->session()->regenerate();

            
            return redirect()->intended(route('panel'));
        }

        
        RateLimiter::hit($claveIntentos, self::VENTANA_SEGUNDOS);

        
        if (RateLimiter::attempts($claveIntentos) >= self::MAX_INTENTOS) {
            RateLimiter::hit($claveBloqueo, self::BLOQUEO_SEGUNDOS);
            RateLimiter::clear($claveIntentos);

            return $this->respuestaBloqueado(self::BLOQUEO_SEGUNDOS);
        }

        
        return back()
            ->withErrors(['credenciales' => 'Las credenciales ingresadas no son correctas.'])
            ->onlyInput('correo');
    }

    
    private function claveIntentos(string $correo, ?string $ip): string
    {
        return 'login:' . sha1($correo . '|' . $ip);
    }

    
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
