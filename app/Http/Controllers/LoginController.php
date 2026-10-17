<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    // GET /login -> muestra el formulario
    public function showLoginForm()
    {
        return view('login');
    }

    // POST /login -> valida y autentica
    public function authenticate(Request $request)
    {
        // CA-02.1 / CA-02.4: ambos campos son obligatorios
        $datos = $request->validate([
            'correo' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'correo.required' => 'El correo electrónico es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        // El correo se guardó en minúsculas al registrarse, se normaliza igual aquí
        $credenciales = [
            'correo' => Str::lower(trim($datos['correo'])),
            'password' => $datos['password'],
        ];

        // CA-02.2: Auth::attempt busca por correo y compara el password contra el hash
        if (Auth::attempt($credenciales)) {
            // Evita fijación de sesión
            $request->session()->regenerate();

            // CA-02.5: redirige al área privada (o a la URL privada que intentó abrir)
            return redirect()->intended(route('panel'));
        }

        // CA-02.3: mensaje genérico, no revela si falló el correo o la contraseña
        return back()
            ->withErrors(['credenciales' => 'Las credenciales ingresadas no son correctas.'])
            ->onlyInput('correo');
    }

    // POST /logout -> cierre de sesión básico (apoyo para probar; la HU-04 lo ampliará)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
