const MESES = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
const DIAS_SEMANA = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

function dosCifras(numero) {
  return String(numero).padStart(2, '0');
}

function fechaATexto(fecha) {
  return fecha.getFullYear() + '-' + dosCifras(fecha.getMonth() + 1) + '-' + dosCifras(fecha.getDate());
}

function textoAFecha(texto) {
  const partes = texto.split('-');
  return new Date(Number(partes[0]), Number(partes[1]) - 1, Number(partes[2]));
}

function fechaDeHoy() {
  return fechaATexto(new Date());
}

function sumarDias(texto, dias) {
  const fecha = textoAFecha(texto);
  fecha.setDate(fecha.getDate() + dias);
  return fechaATexto(fecha);
}

function fechaCorta(texto) {
  const fecha = textoAFecha(texto);
  return fecha.getDate() + ' ' + MESES[fecha.getMonth()].slice(0, 3) + ' ' + fecha.getFullYear();
}

function fechaLarga(texto) {
  const fecha = textoAFecha(texto);
  return DIAS_SEMANA[fecha.getDay()] + ', ' + fecha.getDate() + ' de ' + MESES[fecha.getMonth()];
}

function nombreDelMes(anio, mes) {
  const nombre = MESES[mes];
  return nombre.charAt(0).toUpperCase() + nombre.slice(1) + ' ' + anio;
}

function rangoDeFechas(tarea) {
  if (tarea.fecha_inicio === tarea.fecha_fin) {
    return fechaCorta(tarea.fecha_inicio);
  }

  return fechaCorta(tarea.fecha_inicio) + ' – ' + fechaCorta(tarea.fecha_fin);
}

function celdasDelMes(anio, mes) {
  const celdas = [];
  const primerDia = new Date(anio, mes, 1).getDay();
  const espaciosVacios = (primerDia + 6) % 7;
  const totalDias = new Date(anio, mes + 1, 0).getDate();

  for (let i = 0; i < espaciosVacios; i++) {
    celdas.push(null);
  }

  for (let dia = 1; dia <= totalDias; dia++) {
    celdas.push(fechaATexto(new Date(anio, mes, dia)));
  }

  return celdas;
}
