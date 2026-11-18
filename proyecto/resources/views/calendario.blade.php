@extends('layouts.app')

@section('titulo', 'Calendario')

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
          <li>
            <a class="enlace-menu" href="{{ route('panel') }}">
              <i class="bx bx-home-alt-2"></i><span>Inicio</span>
            </a>
          </li>
          <li>
            <a class="enlace-menu" href="{{ route('tareas') }}">
              <i class="bx bx-task"></i><span>Mis tareas</span>
            </a>
          </li>
          <li>
            <a class="enlace-menu enlace-menu-activo" href="{{ route('calendario') }}" aria-current="page">
              <i class="bx bx-calendar"></i><span>Calendario</span>
            </a>
          </li>
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
            <span class="sobre-titulo">Planifica con claridad</span>
            <h1>Calendario</h1>
            <p>Elige un día y construye tu agenda.</p>
          </div>
          <button class="boton-principal" id="boton-anadir-tarea" type="button">
            <i class="bx bx-plus"></i>
            <span>Añadir tarea</span>
          </button>
        </section>

        <div class="paneles">
          <section class="tarjeta">
            <header class="encabezado-tarjeta">
              <div class="controles-mes">
                <button class="boton-mes" id="boton-mes-anterior" type="button" aria-label="Mes anterior"><i class="bx bx-chevron-left"></i></button>
                <h2 id="mes-visible"></h2>
                <button class="boton-mes" id="boton-mes-siguiente" type="button" aria-label="Mes siguiente"><i class="bx bx-chevron-right"></i></button>
              </div>
              <button class="boton-mes" id="boton-hoy" type="button">Hoy</button>
            </header>

            <div class="dias-semana">
              <span>Lun</span>
              <span>Mar</span>
              <span>Mié</span>
              <span>Jue</span>
              <span>Vie</span>
              <span>Sáb</span>
              <span>Dom</span>
            </div>

            <div class="cuadricula-dias" id="cuadricula-dias"></div>

            <p class="nota-calendario">Los puntos indican días con tareas. Las fechas anteriores a hoy están bloqueadas para agendar.</p>
          </section>

          <aside class="tarjeta">
            <span class="sobre-titulo">Agenda del día</span>
            <h2 id="titulo-agenda"></h2>
            <ul class="lista-tareas" id="lista-tareas-dia"></ul>
            <button class="boton-agendar" id="boton-agendar-dia" type="button">
              <i class="bx bx-plus"></i>
              <span>Agendar en este día</span>
            </button>
          </aside>
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
  <script src="{{ asset('js/sesion.js') }}"></script>
  <script src="{{ asset('js/fechas.js') }}"></script>
  <script src="{{ asset('js/datos.js') }}"></script>
  <script src="{{ asset('js/comunes.js') }}"></script>
  <script src="{{ asset('js/calendario.js') }}"></script>
@endsection