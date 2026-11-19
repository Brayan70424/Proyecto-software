@extends('layouts.app')

@section('titulo', 'Restablecer contraseña')

@section('contenido')
  <div class="contenedor-autenticacion">
    <div class="tarjeta tarjeta-auth">
      <div class="encabezado-auth">
        <div class="marca-icono-grande">
          <img src="{{ asset('assets/logo-hoja.png') }}" alt="CalenDay" class="marca-imagen">
        </div>
        <h1>Restablecer contraseña</h1>
        <p>Escribe tu nueva contraseña. Debe tener al menos 8 caracteres, una mayúscula, una minúscula y un número.</p>
      </div>

      @if ($errors->any())
        <div class="aviso-error" role="alert">
          <ul>
            @foreach ($errors->all() as $mensaje)
              <li>{{ $mensaje }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('password.update') }}" novalidate>
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="correo" value="{{ old('correo', $correo) }}">

        <div class="campo">
          <label for="password">Nueva contraseña</label>
          <div class="input-con-icono">
            <i class="bx bx-lock-alt"></i>
            <input type="password" id="password" name="password" autocomplete="new-password" required autofocus placeholder="Mínimo 8 caracteres">
          </div>
          @error('password')
            <small class="error-campo">{{ $message }}</small>
          @enderror
        </div>

        <div class="campo">
          <label for="password_confirmation">Confirmar contraseña</label>
          <div class="input-con-icono">
            <i class="bx bx-lock-alt"></i>
            <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required placeholder="Repite tu contraseña">
          </div>
        </div>

        <button type="submit" class="boton-principal boton-ancho-total">
          <span>Cambiar contraseña</span>
          <i class="bx bx-check"></i>
        </button>
      </form>

      <p class="texto-pie-auth">
        <a href="{{ route('login') }}"><i class="bx bx-arrow-back"></i> Volver a iniciar sesión</a>
      </p>
    </div>
  </div>
  @section('estilos-extras')
  <link rel="stylesheet" href="{{ asset('css/estilos-f.css') }}">
@endsection
@endsection
