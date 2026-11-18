<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistroRequest;
use App\Models\Usuario;

class RegistroController extends Controller
{
    
    public function create()
    {
        return view('registro');
    }

    
    public function store(RegistroRequest $request)
    {
        
        $datos = $request->safe()->only(['nombre', 'correo', 'password']);

        
        Usuario::create($datos);

        
        return redirect()
            ->route('login')
            ->with('exito', 'Cuenta creada correctamente. Ya puedes iniciar sesión.');
    }
}
