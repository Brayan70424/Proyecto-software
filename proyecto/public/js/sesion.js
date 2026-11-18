function usuarioActual() {
  return window.usuarioAutenticado || { nombre: 'Invitado', correo: '' };
}

async function cerrarSesion() {
  const token = document.querySelector('meta[name="csrf-token"]')?.content;
  await fetch('/logout', { method: 'POST', headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' } });
  window.location.href = '/login';
}
