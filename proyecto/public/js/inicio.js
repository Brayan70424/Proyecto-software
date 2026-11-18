function contarPorEstado(tareas, estado) {
  return tareas.filter(function (tarea) {
    return tarea.estado === estado;
  }).length;
}

function pintarProgreso(tareasDeHoy) {
  const completadas = contarPorEstado(tareasDeHoy, 'Completada');
  let porcentaje = 0;
  let titulo = 'Sigue avanzando';

  if (tareasDeHoy.length > 0) {
    porcentaje = Math.round((completadas / tareasDeHoy.length) * 100);
  }

  if (tareasDeHoy.length === 0) {
    titulo = 'Día libre';
  } else if (porcentaje === 100) {
    titulo = '¡Día completado!';
  }

  document.getElementById('tareas-completadas-hoy').textContent = completadas;
  document.getElementById('tareas-totales-hoy').textContent = tareasDeHoy.length;
  document.getElementById('porcentaje-progreso').textContent = porcentaje + '%';
  document.getElementById('titulo-progreso').textContent = titulo;
  document.getElementById('anillo-progreso').style.setProperty('--porcentaje', porcentaje);
}

function pintarMiniCalendario(hoy) {
  const fecha = textoAFecha(hoy);
  const contenedor = document.getElementById('mini-calendario');
  const iniciales = ['L', 'M', 'X', 'J', 'V', 'S', 'D'];

  document.getElementById('titulo-mini-calendario').textContent = nombreDelMes(fecha.getFullYear(), fecha.getMonth());
  contenedor.innerHTML = '';

  for (const inicial of iniciales) {
    const celda = document.createElement('span');
    celda.className = 'mini-dia-semana';
    celda.textContent = inicial;
    contenedor.appendChild(celda);
  }

  for (const dia of celdasDelMes(fecha.getFullYear(), fecha.getMonth())) {
    const celda = document.createElement('span');

    if (dia !== null) {
      celda.textContent = textoAFecha(dia).getDate();

      if (tareasDelDia(dia).length > 0) {
        celda.classList.add('mini-dia-con-tareas');
      }

      if (dia === hoy) {
        celda.classList.add('mini-dia-hoy');
      }
    }

    contenedor.appendChild(celda);
  }
}

function actualizarInicio() {
  const hoy = fechaDeHoy();
  const todas = listarTareas();
  const tareasDeHoy = tareasDelDia(hoy);

  document.getElementById('saludo-usuario').textContent = usuarioActual().nombre;
  document.getElementById('fecha-de-hoy').textContent = fechaLarga(hoy);
  document.getElementById('contador-hoy').textContent = tareasDeHoy.length + ' tareas';
  document.getElementById('cantidad-pendiente').textContent = contarPorEstado(todas, 'Pendiente');
  document.getElementById('cantidad-en-progreso').textContent = contarPorEstado(todas, 'En progreso');
  document.getElementById('cantidad-completada').textContent = contarPorEstado(todas, 'Completada');

  pintarProgreso(tareasDeHoy);
  pintarLista('lista-tareas-hoy', tareasDeHoy, 'No tienes tareas para hoy.');
  pintarMiniCalendario(hoy);
}

prepararPagina(actualizarInicio);
