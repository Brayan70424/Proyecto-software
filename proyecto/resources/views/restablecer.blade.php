@extends('layouts.app')

@section('titulo', 'Nueva contraseña')

@section('contenido')
    <h1>Nueva contraseña</h1>

    @if ($errors->any())
        <div class="alerta error">
            <ul>
                @foreach ($errors->all() as $mensaje)
                    <li>{{ $mensaje }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" novalidate>
        @csrf

        {{-- El token viene en la URL del correo y viaja oculto en el formulario --}}
        <input type="hidden" name="token" value="{{ $token }}">

        <label for="correo">Correo electrónico</label>
        <input type="email" id="correo" name="correo" value="{{ old('correo', $correo) }}" autocomplete="email">

        <label for="password">Nueva contraseña</label>
        <input type="password" id="password" name="password" autocomplete="new-password">

        <label for="password_confirmation">Confirmar nueva contraseña</label>
        <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password">

        <button type="submit">Cambiar contraseña</button>
    </form>

    <p class="enlace"><a href="{{ route('login') }}">Volver a iniciar sesión</a></p>
@endsection
