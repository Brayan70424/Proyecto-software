@extends('layouts.app')

@section('titulo', 'Panel privado')

@section('contenido')
    <h1>Panel privado</h1>
    <p>Bienvenido, <strong>{{ auth()->user()->nombre }}</strong>.</p>
    <p>Esta página solo es visible para usuarios autenticados.</p>

    {{-- HU-04 · CA-04.1: opción para cerrar sesión (POST + @csrf) --}}
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Cerrar sesión</button>
    </form>
@endsection
