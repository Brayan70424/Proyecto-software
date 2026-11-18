@extends('layouts.app')

@section('titulo', 'Recuperar contraseña')

@push('estilos')
  <link rel="stylesheet" href="{{ asset('css/estilos-f.css') }}">
@endpush

@section('contenido')
  <div class="contenedor-autenticacion">
    <div class="tarjeta tarjeta-auth">
      <div class="encabezado-auth">
        <div class="marca-icono-grande">
          <img src="{{ asset('assets/logo-hoja.png') }}" alt="CalenDay" class="marca-imagen">
        </div>
        <h1>Recuperar contraseña</h1>
        <p>Escribe tu correo y, si está registrado, te enviaremos un enlace para crear una nueva contraseña.</p>
      </div>

      @if (session('exito'))
        <div class="aviso-exito" role="status">
          {{ session('exito') }}
        </div>
      @endif

      @if ($errors->any())
        <div class="aviso-error" role="alert">
          <ul>
            @foreach ($errors->all() as $mensaje)
              <li>{{ $mensaje }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf

        <div class="campo">
          <label for="correo">Correo electrónico</label>
          <div class="input-con-icono">
            <i class="bx bx-envelope"></i>
            <input type="email" id="correo" name="correo" value="{{ old('correo') }}" autocomplete="email" required autofocus placeholder="tu@correo.com">
          </div>
          @error('correo')
            <small class="error-campo">{{ $message }}</small>
          @enderror
        </div>

        <button type="submit" class="boton-principal boton-ancho-total">
          <span>Enviar enlace</span>
          <i class="bx bx-mail-send"></i>
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