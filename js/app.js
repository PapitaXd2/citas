// ---------- 1. Horarios disponibles con fetch (agendar cita) ----------
const campoFecha = document.getElementById('fecha');
const campoHora = document.getElementById('hora');
const mensajeHoras = document.getElementById('mensaje-horas');

async function cargarHorarios() {
  campoHora.replaceChildren();
  campoHora.disabled = true;
  if (!campoFecha.value) return;

  mensajeHoras.textContent = 'Buscando horarios…';
  try {
    const respuesta = await 
    fetch(`api/horarios.php?fecha=${encodeURIComponent(campoFecha.value)}`);
    const datos = await respuesta.json();
    mensajeHoras.textContent = datos.mensaje;
    llenarHoras(datos.horas);
  } catch (error) {
    mensajeHoras.textContent = 'No se pudieron cargar los horarios. Intenta de nuevo.';
  }
}

function llenarHoras(horas) {
  const elegida = campoHora.dataset.seleccionada;   // la que había elegido, si regresó con un error
  campoHora.append(new Option(horas.length ? 'Elige una hora' : 'Sin horarios', ''));
  horas.forEach((hora) => {
    campoHora.append(new Option(hora, hora, false, hora === elegida));
  });
  campoHora.disabled = horas.length === 0;
}

if (campoFecha && campoHora) {
  campoFecha.addEventListener('change', cargarHorarios);
  if (campoFecha.value) cargarHorarios();   // si la página regresó con un error, recarga las horas
}

// ---------- 2. Contador de caracteres en las notas ----------
const notas = document.getElementById('notas');
const contador = document.getElementById('contador');

function actualizarContador() {
  contador.textContent = `${notas.value.length} / ${notas.maxLength}`;
}

if (notas && contador) {
  notas.addEventListener('input', actualizarContador);
  actualizarContador();
}

// ---------- 3. Confirmar antes de cancelar o eliminar ----------
document.querySelectorAll('form.confirmar').forEach((formulario) => {
  formulario.addEventListener('submit', (evento) => {
    if (!confirm(formulario.dataset.mensaje || '¿Seguro?')) {
      evento.preventDefault();
    }
  });
});
// ---------- 4. Las contraseñas deben coincidir (registro) ----------
const contrasena = document.getElementById('contrasena');
const confirmar = document.getElementById('confirmar');

function validarContrasenas() {
  const coinciden = confirmar.value === contrasena.value;
  confirmar.setCustomValidity(coinciden ? '' : 'Las contraseñas no coinciden');
}

if (confirmar) {
  contrasena.addEventListener('input', validarContrasenas);
  confirmar.addEventListener('input', validarContrasenas);
}

// ---------- 5. Vista previa de la imagen del servicio ----------
const campoImagen = document.getElementById('imagen');
const vistaPrevia = document.getElementById('vista-previa');

if (campoImagen) {
  campoImagen.addEventListener('change', () => {
    const archivo = campoImagen.files[0];
    if (!archivo) return;
    vistaPrevia.src = URL.createObjectURL(archivo);
    vistaPrevia.classList.remove('d-none');
  });
}

// ---------- 6. Los avisos verdes se desvanecen a los 4 segundos ----------
document.querySelectorAll('.aviso').forEach((aviso) => {
  setTimeout(() => aviso.classList.add('ocultar'), 4000);
});
