<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'CalenDay')</title>
    
    <!-- Fuentes y Iconos -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:ital,wght@0,500;0,600;0,700;1,600&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    
    <!-- Hoja de estilos principal -->
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">

    <!-- Pila para inyectar estilos específicos por vista (ej. auth) -->
    @yield('estilos-extras')
</head>
<body>
    <script>window.usuarioAutenticado = @json(['nombre' => auth()->user()->nombre ?? 'Invitado', 'correo' => auth()->user()->correo ?? '']);</script>

    <!-- Aquí es donde Laravel inyecta el contenido -->
    @yield('contenido')

</body>
</html>