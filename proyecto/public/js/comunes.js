const CAMPOS_CON_ERROR = ['nombre', 'fecha_inicio', 'fecha_fin', 'estado'];

let actualizarPagina = function () {};
let idTareaEnEdicion = null;
let idTareaPorEliminar = null;
let temporizadorAviso = null;

function mostrarUsuario() {
  const usuario = usuarioActual();

  document.getElementById('nombre-usuario').textContent = usuario.nombre;
  document.getElementById('correo-usuario').textContent = usuario.correo;
  document.getElementById('inicial-usuario').textContent = usuario.nombre.charAt(0);
}

function mostrarAviso(texto) {
  const aviso = document.getElementById('aviso');

  aviso.textContent = texto;
  aviso.classList.add('aviso-visible');
  clearTimeout(temporizadorAviso);

  temporizadorAviso = setTimeout(function () {
    aviso.classList.remove('aviso-visible');
  }, 3000);
}

function claseDeEstado(estado) {
  if (estado === 'Completada') {
    return 'completada';
  }

  if (estado === 'En progreso') {
    return 'en-progreso';
  }

  return 'pendiente';
}

function crearElementoTarea(tarea) {
  const elemento = document.createElement('li');

  elemento.className = 'elemento-tarea';
  elemento.innerHTML = `
    <input class="casilla-tarea" type="checkbox" title="Marcar como completada">
    <div class="detalle-tarea">
      <span class="nombre-tarea"></span>
      <span class="fechas-tarea"><i class="bx bx-calendar"></i> <span class="texto-fechas"></span></span>
    </div>
    <span class="etiqueta-estado"></span>
    <div class="acciones-tarea">
      <button class="boton-accion boton-ver" type="button" title="Ver tarea"><i class="bx bx-show"></i></button>
      <button class="boton-accion boton-editar" type="button" title="Editar tarea"><i class="bx bx-edit-alt"></i></button>
      <button class="boton-accion boton-eliminar" type="button" title="Eliminar tarea"><i class="bx bx-trash"></i></button>
    </div>
  `;

  const casilla = elemento.querySelector('.casilla-tarea');
  const etiqueta = elemento.querySelector('.etiqueta-estado');

  casilla.checked = tarea.estado === 'Completada';
  elemento.querySelector('.nombre-tarea').textContent = tarea.nombre;
  elemento.querySelector('.texto-fechas').textContent = rangoDeFechas(tarea);
  etiqueta.textContent = tarea.estado;
  etiqueta.classList.add('estado-' + claseDeEstado(tarea.estado));

  if (tarea.estado === 'Completada') {
    elemento.classList.add('elemento-tarea-completada');
  }

  casilla.addEventListener('change', function () {
    marcarTarea(tarea.id, casilla.checked);
  });

  elemento.querySelector('.boton-ver').addEventListener('click', function () {
    abrirDetalle(tarea.id);
  });

  elemento.querySelector('.boton-editar').addEventListener('click', function () {
    abrirFormularioEditar(tarea.id);
  });

  elemento.querySelector('.boton-eliminar').addEventListener('click', function () {
    abrirEliminar(tarea.id);
  });

  return elemento;
}

function pintarLista(idLista, tareas, mensajeVacio) {
  const lista = document.getElementById(idLista);

  lista.innerHTML = '';

  if (tareas.length === 0) {
    const vacio = document.createElement('li');
    vacio.className = 'mensaje-vacio';
    vacio.textContent = mensajeVacio;
    lista.appendChild(vacio);
    return;
  }

  for (const tarea of tareas) {
    lista.appendChild(crearElementoTarea(tarea));
  }
}

async function marcarTarea(id, marcada) {
  let estado = 'Pendiente';

  if (marcada) {
    estado = 'Completada';
  }

  if (!(await cambiarEstado(id, estado))) {
    mostrarAviso('La tarea ya no existe.');
  }

  try { await inicializarTareas(); actualizarPagina(); }
  catch (error) { mostrarAviso(error.message || 'No fue posible cargar las tareas.'); }
}

function mostrarErrores(errores) {
  for (const campo of CAMPOS_CON_ERROR) {
    document.getElementById('error-' + campo).textContent = errores[campo] || '';
    document.getElementById(campo).classList.toggle('campo-con-error', errores[campo] !== undefined);
  }

  document.getElementById('error-general').textContent = errores.general || '';
}

function abrirFormularioNuevo(fecha) {
  idTareaEnEdicion = null;

  document.getElementById('formulario-tarea').reset();
  document.getElementById('titulo-ventana-tarea').textContent = 'Nueva tarea';
  document.getElementById('fecha_inicio').value = fecha;
  document.getElementById('fecha_fin').value = fecha;
  mostrarErrores({});
  document.getElementById('ventana-tarea').showModal();
}

function abrirFormularioEditar(id) {
  const tarea = buscarTarea(id);

  if (tarea === null) {
    mostrarAviso('La tarea ya no existe.');
    actualizarPagina();
    return;
  }

  idTareaEnEdicion = id;

  document.getElementById('titulo-ventana-tarea').textContent = 'Editar tarea';
  document.getElementById('nombre').value = tarea.nombre;
  document.getElementById('descripcion').value = tarea.descripcion;
  document.getElementById('fecha_inicio').value = tarea.fecha_inicio;
  document.getElementById('fecha_fin').value = tarea.fecha_fin;
  document.getElementById('estado').value = tarea.estado;
  mostrarErrores({});
  document.getElementById('ventana-tarea').showModal();
}

async function guardarFormulario(evento) {
  evento.preventDefault();

  const datos = {
    nombre: document.getElementById('nombre').value.trim(),
    descripcion: document.getElementById('descripcion').value.trim(),
    fecha_inicio: document.getElementById('fecha_inicio').value,
    fecha_fin: document.getElementById('fecha_fin').value,
    estado: document.getElementById('estado').value
  };

  let resultado;

  if (idTareaEnEdicion === null) {
    resultado = await crearTarea(datos);
  } else {
    resultado = await editarTarea(idTareaEnEdicion, datos);
  }

  if (!resultado.guardada) {
    mostrarErrores(resultado.errores);
    return;
  }

  document.getElementById('ventana-tarea').close();

  if (idTareaEnEdicion === null) {
    mostrarAviso('Tarea creada.');
  } else {
    mostrarAviso('Cambios guardados.');
  }

  try { await inicializarTareas(); actualizarPagina(); }
  catch (error) { mostrarAviso(error.message || 'No fue posible cargar las tareas.'); }
}

function abrirDetalle(id) {
  const tarea = buscarTarea(id);

  if (tarea === null) {
    mostrarAviso('La tarea ya no existe.');
    actualizarPagina();
    return;
  }

  document.getElementById('detalle-nombre').textContent = tarea.nombre;
  document.getElementById('detalle-descripcion').textContent = tarea.descripcion || 'Sin descripción.';
  document.getElementById('detalle-fecha-inicio').textContent = fechaCorta(tarea.fecha_inicio);
  document.getElementById('detalle-fecha-fin').textContent = fechaCorta(tarea.fecha_fin);
  document.getElementById('detalle-estado').textContent = tarea.estado;
  document.getElementById('ventana-detalle').showModal();
}

function abrirEliminar(id) {
  const tarea = buscarTarea(id);

  if (tarea === null) {
    mostrarAviso('La tarea ya no existe.');
    actualizarPagina();
    return;
  }

  idTareaPorEliminar = id;

  document.getElementById('eliminar-nombre').textContent = tarea.nombre;
  document.getElementById('ventana-eliminar').showModal();
}

async function confirmarEliminar() {
  if (await eliminarTarea(idTareaPorEliminar)) {
    mostrarAviso('Tarea eliminada.');
  } else {
    mostrarAviso('La tarea ya no existe.');
  }

  document.getElementById('ventana-eliminar').close();
  try { await inicializarTareas(); actualizarPagina(); }
  catch (error) { mostrarAviso(error.message || 'No fue posible cargar las tareas.'); }
}

async function prepararPagina(funcionActualizar) {
  actualizarPagina = funcionActualizar;

  mostrarUsuario();

  document.getElementById('boton-cerrar-sesion').addEventListener('click', cerrarSesion);
  document.getElementById('formulario-tarea').addEventListener('submit', guardarFormulario);
  document.getElementById('boton-confirmar-eliminar').addEventListener('click', confirmarEliminar);

  document.getElementById('boton-anadir-tarea').addEventListener('click', function () {
    abrirFormularioNuevo(fechaDeHoy());
  });

  for (const boton of document.querySelectorAll('.boton-cerrar-ventana')) {
    boton.addEventListener('click', function () {
      boton.closest('dialog').close();
    });
  }

  try { await inicializarTareas(); actualizarPagina(); }
  catch (error) { mostrarAviso(error.message || 'No fue posible cargar las tareas.'); }
}
