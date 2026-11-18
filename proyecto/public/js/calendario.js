let diaSeleccionado = fechaDeHoy();
let anioVisible = new Date().getFullYear();
let mesVisible = new Date().getMonth();

function crearBotonDia(dia, hoy) {
  const boton = document.createElement('button');
  const tareas = tareasDelDia(dia);

  boton.type = 'button';
  boton.className = 'dia-calendario';
  boton.innerHTML = '<span class="numero-dia"></span><span class="puntos-tareas"></span>';
  boton.querySelector('.numero-dia').textContent = textoAFecha(dia).getDate();
  boton.title = fechaLarga(dia) + ' · ' + tareas.length + ' tareas';

  if (dia < hoy) {
    boton.classList.add('dia-calendario-pasado');
  }

  if (dia === hoy) {
    boton.classList.add('dia-calendario-hoy');
  }

  if (dia === diaSeleccionado) {
    boton.classList.add('dia-calendario-seleccionado');
  }

  for (const tarea of tareas.slice(0, 4)) {
    const punto = document.createElement('span');
    punto.className = 'punto punto-' + claseDeEstado(tarea.estado);
    boton.querySelector('.puntos-tareas').appendChild(punto);
  }

  boton.addEventListener('click', function () {
    diaSeleccionado = dia;
    actualizarCalendario();
  });

  return boton;
}

function pintarMes() {
  const hoy = fechaDeHoy();
  const cuadricula = document.getElementById('cuadricula-dias');

  document.getElementById('mes-visible').textContent = nombreDelMes(anioVisible, mesVisible);
  cuadricula.innerHTML = '';

  for (const dia of celdasDelMes(anioVisible, mesVisible)) {
    if (dia === null) {
      cuadricula.appendChild(document.createElement('span'));
    } else {
      cuadricula.appendChild(crearBotonDia(dia, hoy));
    }
  }
}

function pintarAgenda() {
  const esDiaPasado = diaSeleccionado < fechaDeHoy();

  document.getElementById('titulo-agenda').textContent = fechaLarga(diaSeleccionado);
  document.getElementById('boton-agendar-dia').disabled = esDiaPasado;
  pintarLista('lista-tareas-dia', tareasDelDia(diaSeleccionado), 'No hay tareas para este día.');
}

function actualizarCalendario() {
  pintarMes();
  pintarAgenda();
}

function cambiarMes(cantidad) {
  const fecha = new Date(anioVisible, mesVisible + cantidad, 1);

  anioVisible = fecha.getFullYear();
  mesVisible = fecha.getMonth();
  actualizarCalendario();
}

function irAHoy() {
  diaSeleccionado = fechaDeHoy();
  anioVisible = new Date().getFullYear();
  mesVisible = new Date().getMonth();
  actualizarCalendario();
}

function prepararCalendario() {
  document.getElementById('boton-hoy').addEventListener('click', irAHoy);

  document.getElementById('boton-mes-anterior').addEventListener('click', function () {
    cambiarMes(-1);
  });

  document.getElementById('boton-mes-siguiente').addEventListener('click', function () {
    cambiarMes(1);
  });

  document.getElementById('boton-agendar-dia').addEventListener('click', function () {
    abrirFormularioNuevo(diaSeleccionado);
  });
}

prepararCalendario();
prepararPagina(actualizarCalendario);
