<?php

namespace App\Support;

// Reglas de seguridad de la contraseña definidas en la HU-01.
// Se reutilizan en el registro (HU-01) y en la recuperación (HU-03 · CA-03.5)
// para que ambas pantallas exijan exactamente lo mismo.
class ReglasPassword
{
    public static function rules(): array
    {
        return [
            'bail',
            'required',
            'string',
            'min:8',          // mínimo 8 caracteres
            'max:64',
            'regex:/[a-z]/',  // al menos una minúscula
            'regex:/[A-Z]/',  // al menos una mayúscula
            'regex:/[0-9]/',  // al menos un número
            'confirmed',      // debe coincidir con password_confirmation
        ];
    }

    public static function messages(): array
    {
        $regla = 'La contraseña debe tener al menos 8 caracteres, una letra mayúscula, una minúscula y un número.';

        return [
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => $regla,
            'password.regex' => $regla,
            'password.max' => 'La contraseña no puede superar los 64 caracteres.',
            'password.confirmed' => 'La contraseña y su confirmación no coinciden.',
            'password_confirmation.required' => 'Debes confirmar la contraseña.',
        ];
    }
}
