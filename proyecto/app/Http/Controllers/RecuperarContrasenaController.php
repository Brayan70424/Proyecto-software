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
    
    public function formularioSolicitud()
    {
        return view('recuperar');
    }

    
    public function enviarEnlace(Request $request)
    {
        $request->merge(['correo' => Str::lower(trim((string) $request->input('correo')))]);

        
        $request->validate([
            'correo' => ['required', 'string', 'email'],
        ], [
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El correo electrónico no tiene un formato válido.',
        ]);

        
        
        
        try {
            Password::sendResetLink(['correo' => $request->input('correo')]);
        } catch (\Throwable $e) {
            
            
            
            Log::error('No se pudo enviar el correo de recuperación: ' . $e->getMessage());

            if (config('app.debug')) {
                throw $e;
            }
        }

        
        return back()->with(
            'exito',
            'Si el correo está registrado, recibirás un enlace para restablecer tu contraseña.'
        );
    }

    
    public function formularioRestablecer(Request $request, string $token)
    {
        return view('restablecer', [
            'token' => $token,
            'correo' => $request->query('correo'),
        ]);
    }

    
    public function restablecer(RestablecerContrasenaRequest $request)
    {
        
        
        $estado = Password::reset(
            $request->safe()->only(['correo', 'password', 'password_confirmation', 'token']),
            function (Usuario $usuario, string $password) {
                
                
                $usuario->forceFill(['password' => $password])->save();
            }
        );

        if ($estado === Password::PASSWORD_RESET) {
            
            return redirect()
                ->route('login')
                ->with('exito', 'Tu contraseña fue actualizada. Ya puedes iniciar sesión.');
        }

        
        return back()
            ->withInput($request->only('correo'))
            ->withErrors(['correo' => 'El enlace de recuperación no es válido o ha expirado. Solicita uno nuevo.']);
    }
}
