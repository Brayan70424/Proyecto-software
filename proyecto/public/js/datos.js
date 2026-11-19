const ESTADOS = ['Pendiente', 'En progreso', 'Completada'];
let tareasEnMemoria = [];

async function inicializarTareas() {
  const respuesta = await fetch('/api/tareas', { headers: { 'Accept': 'application/json' } });
  if (!respuesta.ok) throw new Error('No fue posible cargar las tareas.');
  tareasEnMemoria = await respuesta.json();
}

function listarTareas() {
  return [...tareasEnMemoria].sort((a, b) => a.fecha_inicio.localeCompare(b.fecha_inicio));
}
function buscarTarea(id) {
  return tareasEnMemoria.find(t => Number(t.id) === Number(id)) || null;
}
function tareasDelDia(fecha) {
  return listarTareas().filter(t => t.fecha_inicio <= fecha && fecha <= t.fecha_fin);
}
function validarTarea(datos) {
  const errores = {};
  if (!datos.nombre) errores.nombre = 'Escribe el nombre de la tarea.';
  else if (datos.nombre.length > 80) errores.nombre = 'El nombre no puede tener más de 80 caracteres.';
  if (datos.descripcion && datos.descripcion.length > 2000) errores.descripcion = 'La descripción no puede tener más de 2000 caracteres.';
  if (!datos.fecha_inicio) errores.fecha_inicio = 'Elige la fecha de inicio.';
  if (!datos.fecha_fin) errores.fecha_fin = 'Elige la fecha de fin.';
  else if (datos.fecha_inicio && datos.fecha_fin < datos.fecha_inicio) errores.fecha_fin = 'La fecha de fin no puede ser anterior a la fecha de inicio.';
  if (!ESTADOS.includes(datos.estado)) errores.estado = 'Elige un estado válido: Pendiente, En progreso o Completada.';
  return errores;
}
function hayErrores(errores) { return Object.keys(errores).length > 0; }
async function peticionTarea(url, metodo, datos) {
  const respuesta = await fetch(url, {
    method: metodo,
    headers: {
      'Content-Type': 'application/json', 'Accept': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: datos ? JSON.stringify(datos) : undefined
  });
  const resultado = await respuesta.json().catch(() => ({}));
  if (respuesta.status === 422) {
    const errores = {};
    Object.keys(resultado.errors || {}).forEach(campo => errores[campo] = resultado.errors[campo][0]);
    throw { errores };
  }
  if (!respuesta.ok) throw { errores: { general: respuesta.status === 404 ? 'La tarea que buscas ya no existe.' : 'No se pudo completar la operación.' } };
  return resultado;
}
async function crearTarea(datos) {
  const errores = validarTarea(datos);
  if (hayErrores(errores)) return { guardada: false, errores };
  try { tareasEnMemoria.push(await peticionTarea('/api/tareas', 'POST', datos)); return { guardada: true, errores: {} }; }
  catch (e) { return { guardada: false, errores: e.errores || { general: 'Error al guardar la tarea.' } }; }
}
async function editarTarea(id, datos) {
  const errores = validarTarea(datos);
  if (hayErrores(errores)) return { guardada: false, errores };
  try {
    const actualizada = await peticionTarea('/api/tareas/' + id, 'PUT', datos);
    tareasEnMemoria = tareasEnMemoria.map(t => Number(t.id) === Number(id) ? actualizada : t);
    return { guardada: true, errores: {} };
  } catch (e) { return { guardada: false, errores: e.errores || { general: 'Error al editar la tarea.' } }; }
}
async function cambiarEstado(id, estado) {
  const tarea = buscarTarea(id); if (!tarea || !ESTADOS.includes(estado)) return false;
  const datos = { nombre: tarea.nombre, descripcion: tarea.descripcion || '', fecha_inicio: tarea.fecha_inicio, fecha_fin: tarea.fecha_fin, estado };
  const resultado = await editarTarea(id, datos); return resultado.guardada;
}
async function eliminarTarea(id) {
  try { await peticionTarea('/api/tareas/' + id, 'DELETE'); tareasEnMemoria = tareasEnMemoria.filter(t => Number(t.id) !== Number(id)); return true; }
  catch (_) { return false; }
}
