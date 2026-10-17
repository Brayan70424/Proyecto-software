<?php

namespace App\Http\Requests;

use App\Models\Usuario;
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

            // CA-01.4: mínimo 8, una mayúscula, una minúscula y un número
            // CA-01.5: 'confirmed' compara con el campo password_confirmation
            'password' => [
                'bail',
                'required',
                'string',
                'min:8',
                'max:64',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'confirmed',
            ],
            'password_confirmation' => ['required'],
        ];
    }

    public function messages(): array
    {
        $reglaPassword = 'La contraseña debe tener al menos 8 caracteres, una letra mayúscula, una minúscula y un número.';

        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede superar los 100 caracteres.',

            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El correo electrónico no tiene un formato válido.',
            'correo.regex' => 'El correo electrónico no tiene un formato válido.',
            'correo.max' => 'El correo electrónico no puede superar los 150 caracteres.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => $reglaPassword,
            'password.regex' => $reglaPassword,
            'password.max' => 'La contraseña no puede superar los 64 caracteres.',
            'password.confirmed' => 'La contraseña y su confirmación no coinciden.',

            'password_confirmation.required' => 'Debes confirmar la contraseña.',
        ];
    }
}
