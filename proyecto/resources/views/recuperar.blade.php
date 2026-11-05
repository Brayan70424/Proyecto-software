@extends('layouts.app')

@section('titulo', 'Recuperar contraseña')

@section('contenido')
    <h1>Recuperar contraseña</h1>
    <p style="font-size:14px;color:#555;margin-top:-6px;">
        Escribe tu correo y, si está registrado, te enviaremos un enlace para crear una nueva contraseña.
    </p>

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

    <form method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf

        <label for="correo">Correo electrónico</label>
        <input type="email" id="correo" name="correo" value="{{ old('correo') }}" autocomplete="email">

        <button type="submit">Enviar enlace</button>
    </form>

    <p class="enlace"><a href="{{ route('login') }}">Volver a iniciar sesión</a></p>
@endsection
