const parametros = new URLSearchParams(window.location.search);
const busquedaActual = (parametros.get('busqueda') || '').trim();

let filtroActual = 'Todas';

function tareasFiltradas(tareas, filtro) {
  return tareas.filter(function (tarea) {
    const coincideEstado = filtro === 'Todas' || tarea.estado === filtro;
    const coincideBusqueda = tarea.nombre.toLowerCase().includes(busquedaActual.toLowerCase());
    return coincideEstado && coincideBusqueda;
  });
}

function actualizarTareas() {
  const todas = listarTareas();

  for (const boton of document.querySelectorAll('.filtro')) {
    const filtro = boton.dataset.filtro;
    boton.querySelector('.cantidad-filtro').textContent = tareasFiltradas(todas, filtro).length;
    boton.classList.toggle('filtro-activo', filtro === filtroActual);
  }

  pintarLista('lista-tareas', tareasFiltradas(todas, filtroActual), 'No hay tareas para mostrar.');
}

function prepararFiltros() {
  const estadoInicial = parametros.get('estado');

  if (ESTADOS.includes(estadoInicial)) {
    filtroActual = estadoInicial;
  }

  for (const boton of document.querySelectorAll('.filtro')) {
    boton.addEventListener('click', function () {
      filtroActual = boton.dataset.filtro;
      actualizarTareas();
    });
  }

  if (busquedaActual !== '') {
    document.getElementById('buscador-tareas').value = busquedaActual;
    document.getElementById('palabra-buscada').textContent = busquedaActual;
    document.getElementById('texto-busqueda').hidden = false;
  }
}

prepararFiltros();
prepararPagina(actualizarTareas);
