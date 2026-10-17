<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo') | Sprint 1</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f2f4f8; margin: 0;
               min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .tarjeta { background: #fff; width: 100%; max-width: 420px; padding: 32px;
                   border-radius: 10px; box-shadow: 0 4px 16px rgba(0,0,0,.08); }
        h1 { margin-top: 0; font-size: 22px; color: #1f2d3d; }
        label { display: block; margin: 14px 0 4px; font-size: 14px; color: #333; }
        input { width: 100%; padding: 10px; border: 1px solid #c4cad4; border-radius: 6px; font-size: 14px; }
        button { width: 100%; margin-top: 20px; padding: 11px; border: 0; border-radius: 6px;
                 background: #2d6cdf; color: #fff; font-size: 15px; cursor: pointer; }
        button:hover { background: #2358b8; }
        .alerta { padding: 10px 14px; border-radius: 6px; margin-bottom: 12px; font-size: 14px; }
        .alerta ul { margin: 0; padding-left: 18px; }
        .error { background: #fdecea; color: #a12622; }
        .exito { background: #e6f4ea; color: #1e6b34; }
        .enlace { text-align: center; margin-top: 16px; font-size: 14px; }
        .enlace a { color: #2d6cdf; }
    </style>
</head>
<body>
    <main class="tarjeta">
        @yield('contenido')
    </main>
</body>
</html>
