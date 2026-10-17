<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistroRequest;
use App\Models\Usuario;

class RegistroController extends Controller
{
    // GET /registro -> muestra el formulario
    public function create()
    {
        return view('registro');
    }

    // POST /registro -> valida (RegistroRequest) y crea el usuario
    public function store(RegistroRequest $request)
    {
        // Solo los 3 campos que se guardan (se descarta password_confirmation)
        $datos = $request->safe()->only(['nombre', 'correo', 'password']);

        // El cast 'hashed' del modelo guarda el password con bcrypt, nunca en texto plano
        Usuario::create($datos);

        // CA-01.6: mensaje de confirmación
        return redirect()
            ->route('login')
            ->with('exito', 'Cuenta creada correctamente. Ya puedes iniciar sesión.');
    }
}
