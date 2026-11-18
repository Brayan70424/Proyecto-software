@extends('layouts.app')

@section('titulo', 'Crear cuenta')

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
        <h1>Crea tu cuenta</h1>
        <p>Empieza a organizar tus días con CalenDay.</p>
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

      {{-- novalidate: se desactiva la validación del navegador para ver los mensajes del servidor --}}
      <form method="POST" action="{{ route('registro.store') }}" novalidate>
        @csrf

        <div class="campo">
          <label for="nombre">Nombre</label>
          <div class="input-con-icono">
            <i class="bx bx-user"></i>
            <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" autocomplete="name" required autofocus placeholder="Tu nombre">
          </div>
          @error('nombre')
            <small class="error-campo">{{ $message }}</small>
          @enderror
        </div>

        <div class="campo">
          <label for="correo">Correo electrónico</label>
          <div class="input-con-icono">
            <i class="bx bx-envelope"></i>
            <input type="email" id="correo" name="correo" value="{{ old('correo') }}" autocomplete="email" required placeholder="tu@correo.com">
          </div>
          @error('correo')
            <small class="error-campo">{{ $message }}</small>
          @enderror
        </div>

        <div class="campo">
          <label for="password">Contraseña</label>
          <div class="input-con-icono">
            <i class="bx bx-lock-alt"></i>
            <input type="password" id="password" name="password" autocomplete="new-password" required placeholder="Mínimo 8 caracteres">
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
          <span>Registrarme</span>
          <i class="bx bx-user-plus"></i>
        </button>
      </form>

      <p class="texto-pie-auth">
        ¿Ya tienes cuenta? <a href="{{ route('login') }}">Iniciar sesión</a>
      </p>
    </div>
  </div>
  @section('estilos-extras')
  <link rel="stylesheet" href="{{ asset('css/estilos-f.css') }}">
@endsection
@endsection