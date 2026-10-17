@extends('layouts.app')

@section('titulo', 'Crear cuenta')

@section('contenido')
    <h1>Crear cuenta</h1>

    @if ($errors->any())
        <div class="alerta error">
            <ul>
                @foreach ($errors->all() as $mensaje)
                    <li>{{ $mensaje }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- novalidate: se desactiva la validación del navegador para ver los mensajes del servidor --}}
    <form method="POST" action="{{ route('registro.store') }}" novalidate>
        @csrf

        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" autocomplete="name">

        <label for="correo">Correo electrónico</label>
        <input type="email" id="correo" name="correo" value="{{ old('correo') }}" autocomplete="email">

        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" autocomplete="new-password">

        <label for="password_confirmation">Confirmar contraseña</label>
        <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password">

        <button type="submit">Registrarme</button>
    </form>

    <p class="enlace">¿Ya tienes cuenta? <a href="{{ route('login') }}">Iniciar sesión</a></p>
@endsection
