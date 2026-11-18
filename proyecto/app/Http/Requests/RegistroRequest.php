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
        return true; 
    }

    
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
            
            'nombre' => ['bail', 'required', 'string', 'max:100'],

            
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
