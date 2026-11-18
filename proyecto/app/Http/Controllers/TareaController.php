<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TareaController extends Controller
{
    private function tareasDelUsuario(Request $request)
    {
        return Tarea::where('usuario_id', $request->user()->getAuthIdentifier());
    }

    public function index(Request $request)
    {
        return response()->json($this->tareasDelUsuario($request)->orderBy('fecha_inicio')->get());
    }

    public function store(Request $request)
    {
        $datos = $this->validar($request);
        $datos['usuario_id'] = $request->user()->getAuthIdentifier();
        return response()->json($this->tareasDelUsuario($request)->create($datos), 201);
    }

    public function update(Request $request, int $id)
    {
        $tarea = $this->tareasDelUsuario($request)->findOrFail($id);
        $tarea->update($this->validar($request));
        return response()->json($tarea->fresh());
    }

    public function destroy(Request $request, int $id)
    {
        $tarea = $this->tareasDelUsuario($request)->findOrFail($id);
        $tarea->delete();
        return response()->json(['mensaje' => 'Tarea eliminada correctamente.']);
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:80'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'estado' => ['required', Rule::in(['Pendiente', 'En progreso', 'Completada'])],
        ], [
            'nombre.required' => 'Escribe el nombre de la tarea.',
            'nombre.max' => 'El nombre no puede tener más de 80 caracteres.',
            'fecha_inicio.required' => 'Elige la fecha de inicio.',
            'fecha_fin.required' => 'Elige la fecha de fin.',
            'fecha_fin.after_or_equal' => 'La fecha de fin no puede ser anterior a la fecha de inicio.',
            'estado.in' => 'Elige un estado válido: Pendiente, En progreso o Completada.',
        ]);
    }
}
