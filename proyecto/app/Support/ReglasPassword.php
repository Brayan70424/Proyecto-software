<?php

namespace App\Support;




class ReglasPassword
{
    public static function rules(): array
    {
        return [
            'bail',
            'required',
            'string',
            'min:8',          
            'max:64',
            'regex:/[a-z]/',  
            'regex:/[A-Z]/',  
            'regex:/[0-9]/',  
            'confirmed',      
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
