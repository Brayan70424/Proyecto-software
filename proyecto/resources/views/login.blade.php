@extends('layouts.app')

@section('titulo', 'Iniciar sesión')

@section('contenido')
    <h1>Iniciar sesión</h1>

    @if (session('exito'))
        <div class="alerta exito">{{ session('exito') }}</div>
    @endif

    @if ($errors->any())
        <div class="alerta error">
            <ul>
                @foreach ($errors->all() as $mensaje)
                    <li>{{ $mensaje }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login.store') }}" novalidate>
        @csrf

        <label for="correo">Correo electrónico</label>
        <input type="email" id="correo" name="correo" value="{{ old('correo') }}" autocomplete="email">

        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" autocomplete="current-password">

        <button type="submit">Entrar</button>
    </form>

    <p class="enlace"><a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a></p>

    <p class="enlace">¿No tienes cuenta? <a href="{{ route('registro') }}">Crear cuenta</a></p>
@endsection
