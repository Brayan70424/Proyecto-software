<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    // POST /logout -> HU-04: cierre de sesión
    public function __invoke(Request $request)
    {
        // Saca al usuario de la sesión
        Auth::guard('web')->logout();

        // CA-04.2: invalida la sesión actual en el servidor (borra sus datos y genera otro ID)
        $request->session()->invalidate();

        // Renueva el token CSRF para que el anterior ya no sirva
        $request->session()->regenerateToken();

        // CA-04.3: redirige al inicio de sesión con un aviso
        return redirect()
            ->route('login')
            ->with('exito', 'Sesión cerrada correctamente.');
    }
}
