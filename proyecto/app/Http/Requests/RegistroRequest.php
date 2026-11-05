<?php

namespace App\Http\Requests;

use App\Models\Usuario;
use App\Support\ReglasPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class RegistroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // El registro es público (visitante)
    }

    // Se ejecuta ANTES de validar: normaliza el correo a minúsculas (CA-01.3)
    protected function prepareForValidation(): void
    {
        $this->merge([
            'correo' => Str::lower(trim((string) $this->input('correo'))),
            'nombre' => trim((string) $this->input('nombre')),
        ]);
    }

    public function rules(): array
    {
        return [
            // CA-01.1 / CA-01.2: campos obligatorios
            'nombre' => ['bail', 'required', 'string', 'max:100'],

            // CA-01.3: formato válido (con punto en el dominio) y único sin distinguir mayúsculas
            'correo' => [
                'bail',
                'required',
                'string',
                'max:150',
                'email:rfc',
                'regex:/^[^@\s]+@[^@\s]+\.[^@\s]+$/',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $existe = Usuario::whereRaw('LOWER(correo) = ?', [$value])->exists();
                    if ($existe) {
                        $fail('Este correo electrónico ya está registrado.');
                    }
                },
            ],

            // CA-01.4 y CA-01.5: mínimo 8, mayúscula, minúscula, número y confirmación
            // (reglas compartidas con la recuperación de contraseña, HU-03)
            'password' => ReglasPassword::rules(),
            'password_confirmation' => ['required'],
        ];
    }

    public function messages(): array
    {
        return array_merge([
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede superar los 100 caracteres.',

            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El correo electrónico no tiene un formato válido.',
            'correo.regex' => 'El correo electrónico no tiene un formato válido.',
            'correo.max' => 'El correo electrónico no puede superar los 150 caracteres.',
        ], ReglasPassword::messages());
    }
}
