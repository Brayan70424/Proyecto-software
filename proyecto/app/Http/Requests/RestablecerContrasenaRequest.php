<?php

namespace App\Http\Requests;

use App\Support\ReglasPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class RestablecerContrasenaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'correo' => Str::lower(trim((string) $this->input('correo'))),
        ]);
    }

    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
            'correo' => ['bail', 'required', 'string', 'email'],

            // CA-03.5: mismas reglas de seguridad que en el registro (HU-01)
            'password' => ReglasPassword::rules(),
            'password_confirmation' => ['required'],
        ];
    }

    public function messages(): array
    {
        return array_merge([
            'token.required' => 'El enlace de recuperación no es válido. Solicita uno nuevo.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El correo electrónico no tiene un formato válido.',
        ], ReglasPassword::messages());
    }
}
