@extends('layouts.app')

@section('titulo', 'Iniciar sesión')

{{-- IMPORTANTE: El push debe estar fuera o dentro, pero el stack debe llamarse exactamente igual --}}
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
        <h1>Iniciar sesión</h1>
        <p>Ingresa tus datos para acceder a tu agenda.</p>
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

      <form method="POST" action="{{ route('login.store') }}" novalidate>
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

        <div class="campo">
          <label for="password">Contraseña</label>
          <div class="input-con-icono">
            <i class="bx bx-lock-alt"></i>
            <input type="password" id="password" name="password" autocomplete="current-password" required placeholder="••••••••">
          </div>
          @error('password')
            <small class="error-campo">{{ $message }}</small>
          @enderror
        </div>

        <button type="submit" class="boton-principal boton-ancho-total">
          <span>Entrar</span>
          <i class="bx bx-right-arrow-alt"></i>
        </button>
      </form>

      <p class="texto-pie-auth">
        <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
      </p>

      <p class="texto-pie-auth">
        ¿No tienes cuenta? <a href="{{ route('registro') }}">Crear cuenta</a>
      </p>
    </div>
  </div>
  @section('estilos-extras')
  <link rel="stylesheet" href="{{ asset('css/estilos-f.css') }}">
@endsection
@endsection