@extends('layouts.app')

@section('titulo', 'Panel privado')

@section('contenido')
  <div class="aplicacion">
    <aside class="menu-lateral">
      <a class="marca marca-lateral" href="{{ route('panel') }}">
        <span class="marca-icono"><img src="{{ asset('assets/logo-hoja.png') }}" alt="" class="marca-imagen"></span>
        <span>CalenDay</span>
      </a>

      <p class="etiqueta-menu">Menú</p>
      <nav>
       <ul class="enlaces-menu">
  <li><a class="enlace-menu" href="{{ route('panel') }}"><i class="bx bx-home-alt-2"></i><span>Inicio</span></a></li>
  <li><a class="enlace-menu" href="{{ route('tareas') }}"><i class="bx bx-task"></i><span>Mis tareas</span></a></li>
  <li><a class="enlace-menu" href="{{ route('calendario') }}"><i class="bx bx-calendar"></i><span>Calendario</span></a></li>
  <li><span class="enlace-menu enlace-menu-inactivo"><i class="bx bx-bar-chart-alt-2"></i><span>Estadísticas</span><span class="etiqueta-pronto">Pronto</span></span></li>
  <li><span class="enlace-menu enlace-menu-inactivo"><i class="bx bx-user"></i><span>Perfil</span><span class="etiqueta-pronto">Pronto</span></span></li>
</ul>
      </nav>

      <ul class="enlaces-inferiores">
        <li><span class="enlace-menu enlace-menu-inactivo"><i class="bx bx-cog"></i><span>Configuración</span><span class="etiqueta-pronto">Pronto</span></span></li>
        <li><button class="enlace-menu" id="boton-cerrar-sesion" type="button"><i class="bx bx-log-out"></i><span>Cerrar sesión</span></button></li>
      </ul>
    </aside>

    <div class="zona-principal">
      <header class="barra-superior">
        <form class="buscador" role="search" action="{{ route('tareas') }}" method="get">
          <i class="bx bx-search"></i>
          <input id="buscador-tareas" type="search" name="busqueda" placeholder="Buscar tareas..." aria-label="Buscar tareas">
        </form>

        <div class="perfil-usuario">
          <span class="avatar-usuario" id="inicial-usuario"></span>
          <span class="datos-usuario">
            <strong id="nombre-usuario"></strong>
            <span id="correo-usuario"></span>
          </span>
        </div>
      </header>

      <main class="contenido-principal">
        <section class="encabezado-pagina">
          <div>
            <span class="sobre-titulo">Tu espacio de hoy</span>
            <h1>¡Bienvenido, <em id="saludo-usuario"></em>!</h1>
            <p id="fecha-de-hoy"></p>
          </div>
          <button class="boton-principal" id="boton-anadir-tarea" type="button">
            <i class="bx bx-plus"></i>
            <span>Añadir tarea</span>
          </button>
        </section>

        <section class="tarjeta-progreso">
          <div>
            <span class="sobre-titulo">Tu progreso</span>
            <p class="numero-progreso"><span id="tareas-completadas-hoy">0</span> <small>de <span id="tareas-totales-hoy">0</span></small></p>
            <p class="texto-progreso">tareas completadas hoy</p>
          </div>
          <div class="resumen-progreso">
            <div class="anillo-progreso" id="anillo-progreso"><span id="porcentaje-progreso">0%</span></div>
            <div>
              <h2 id="titulo-progreso">Sigue avanzando</h2>
              <p class="texto-progreso">Cada pequeña tarea te acerca a tu meta.</p>
            </div>
          </div>
        </section>

        <div class="paneles">
          <section class="tarjeta">
            <header class="encabezado-tarjeta">
              <div>
                <span class="sobre-titulo">En agenda</span>
                <h2>Resumen de hoy</h2>
              </div>
              <span class="contador" id="contador-hoy"></span>
            </header>
            <ul class="lista-tareas" id="lista-tareas-hoy"></ul>
          </section>

          <div class="columna-lateral">
            <section class="tarjeta">
              <header class="encabezado-tarjeta">
                <div>
                  <span class="sobre-titulo">Este mes</span>
                  <h2 id="titulo-mini-calendario"></h2>
                </div>
                <a href="{{ route('calendario') }}">Ver calendario</a>
              </header>
              <div class="mini-calendario" id="mini-calendario"></div>
            </section>

            <section class="tarjeta">
              <header class="encabezado-tarjeta">
                <div>
                  <span class="sobre-titulo">Atajos</span>
                  <h2>Estados</h2>
                </div>
              </header>
              <ul class="lista-estados">
                <li><a href="{{ route('tareas', ['estado' => 'Pendiente']) }}"><span class="punto punto-pendiente"></span><span>Pendiente</span><strong id="cantidad-pendiente">0</strong></a></li>
                <li><a href="{{ route('tareas', ['estado' => 'En progreso']) }}"><span class="punto punto-en-progreso"></span><span>En progreso</span><strong id="cantidad-en-progreso">0</strong></a></li>
                <li><a href="{{ route('tareas', ['estado' => 'Completada']) }}"><span class="punto punto-completada"></span><span>Completada</span><strong id="cantidad-completada">0</strong></a></li>
              </ul>
            </section>
          </div>
        </div>
      </main>
    </div>
  </div>

  <dialog class="ventana" id="ventana-tarea">
    <form id="formulario-tarea" novalidate>
      <h2 id="titulo-ventana-tarea">Nueva tarea</h2>

      <div class="campo">
        <label for="nombre">Nombre</label>
        <input id="nombre" name="nombre" type="text" maxlength="80">
        <small class="error-campo" id="error-nombre"></small>
      </div>

      <div class="campo">
        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" rows="3"></textarea>
      </div>

      <div class="campos-en-fila">
        <div class="campo">
          <label for="fecha_inicio">Fecha de inicio</label>
          <input id="fecha_inicio" name="fecha_inicio" type="date">
          <small class="error-campo" id="error-fecha_inicio"></small>
        </div>
        <div class="campo">
          <label for="fecha_fin">Fecha de fin</label>
          <input id="fecha_fin" name="fecha_fin" type="date">
          <small class="error-campo" id="error-fecha_fin"></small>
        </div>
      </div>

      <div class="campo">
        <label for="estado">Estado</label>
        <select id="estado" name="estado">
          <option value="Pendiente">Pendiente</option>
          <option value="En progreso">En progreso</option>
          <option value="Completada">Completada</option>
        </select>
        <small class="error-campo" id="error-estado"></small>
      </div>

      <small class="error-campo" id="error-general"></small>

      <div class="acciones-ventana">
        <button class="boton-secundario boton-cerrar-ventana" type="button">Cancelar</button>
        <button class="boton-principal" type="submit">Guardar tarea</button>
      </div>
    </form>
  </dialog>

  <dialog class="ventana" id="ventana-detalle">
    <span class="sobre-titulo">Detalle de la tarea</span>
    <h2 id="detalle-nombre"></h2>
    <p id="detalle-descripcion"></p>
    <dl class="datos-detalle">
      <div><dt>Fecha de inicio</dt><dd id="detalle-fecha-inicio"></dd></div>
      <div><dt>Fecha de fin</dt><dd id="detalle-fecha-fin"></dd></div>
      <div><dt>Estado</dt><dd id="detalle-estado"></dd></div>
    </dl>
    <div class="acciones-ventana">
      <button class="boton-secundario boton-cerrar-ventana" type="button">Cerrar</button>
    </div>
  </dialog>

  <dialog class="ventana" id="ventana-eliminar">
    <h2>¿Eliminar esta tarea?</h2>
    <p>Vas a eliminar <strong id="eliminar-nombre"></strong>. Esta acción no se puede deshacer.</p>
    <div class="acciones-ventana">
      <button class="boton-secundario boton-cerrar-ventana" type="button">Cancelar</button>
      <button class="boton-peligro" id="boton-confirmar-eliminar" type="button">Eliminar</button>
    </div>
  </dialog>

  <p class="aviso" id="aviso" role="status"></p>

  {{-- Asegúrate de que tu layout app.blade.php incluya estos scripts o colócalos aquí si no los tiene --}}
  <script src="{{ asset('js/sesion.js') }}"></script>
  <script src="{{ asset('js/fechas.js') }}"></script>
  <script src="{{ asset('js/datos.js') }}"></script>
  <script src="{{ asset('js/comunes.js') }}"></script>
  <script src="{{ asset('js/inicio.js') }}"></script>
@endsection