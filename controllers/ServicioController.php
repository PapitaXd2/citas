<?php
class ServicioController extends Controller
{
    private Servicio $servicios;

    public function __construct(PDO $db)
    {
        $this->servicios = new Servicio($db);
    }

    public function catalogo(): void
    {
        $usuario = $this->exigirSesion();
        $esAdmin = $usuario['rol'] === 'admin';
        $servicios = $this->servicios->todos(!$esAdmin);
        require __DIR__ . '/../views/servicios/lista.php';
    }

    public function formulario(int $id = 0): void
    {
        $this->exigirAdmin();
        $servicio = $id ? $this->servicios->buscarPorId($id) : null;
        $error = null;
        require __DIR__ . '/../views/servicios/formulario.php';
    }

    public function guardar(): void
    {
        $this->exigirAdmin();
        $id = (int) ($_POST['id'] ?? 0);
        $precio = $_POST['precio'] ?? '';
        $datos = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'duracion' => (int) ($_POST['duracion'] ?? 0),
            'precio' => is_numeric($precio) ? (float) $precio : -1,
            'activo' => isset($_POST['activo']),
            'imagen' => null,
        ];

        try {
            if (!preg_match('/^.{3,80}$/u', $datos['nombre'])) {
                throw new InvalidArgumentException('El nombre debe tener entre 3 y 80 caracteres.');
            }
            if (strlen($datos['descripcion']) > 300) {
                throw new InvalidArgumentException('La descripción puede tener máximo 300 caracteres.');
            }
            if ($datos['duracion'] < 10 || $datos['duracion'] > 240) {
                throw new InvalidArgumentException('La duración debe estar entre 10 y 240 minutos.');
            }
            if ($datos['precio'] < 0) {
                throw new InvalidArgumentException('Escribe un precio válido.');
            }

            $datos['imagen'] = $this->subirImagen();

            if ($id) {
                $this->servicios->actualizar($id, $datos);
            } else {
                $this->servicios->crear($datos);
            }
            $this->redirigir('', 'Servicio guardado.');
        } catch (InvalidArgumentException $ex) {
            // Vuelve a mostrar el formulario con lo que escribió y el error
            $error = $ex->getMessage();
            $servicio = ['id' => $id] + $datos;
            if ($servicio['precio'] < 0) {
                $servicio['precio'] = '';
            }
            require __DIR__ . '/../views/servicios/formulario.php';
        }
    }

    // Valida y guarda la imagen; devuelve el nombre del archivo o null si no se subió
    private function subirImagen(): ?string
    {
        $archivo = $_FILES['imagen'] ?? null;
        if (!$archivo || $archivo['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if ($archivo['error'] !== UPLOAD_ERR_OK) {
            throw new InvalidArgumentException('No se pudo subir la imagen.');
        }
        if ($archivo['size'] > 2 * 1024 * 1024) {
            throw new InvalidArgumentException('La imagen debe pesar menos de 2 MB.');
        }

        $permitidos = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $tipo = mime_content_type($archivo['tmp_name']);   // revisa el contenido real, no el nombre
        if (!isset($permitidos[$tipo])) {
            throw new InvalidArgumentException('Solo se aceptan imágenes JPG, PNG o WEBP.');
        }

        $nombre = uniqid('servicio_') . '.' . $permitidos[$tipo];
        if (!move_uploaded_file($archivo['tmp_name'], __DIR__ . '/../uploads/' . $nombre)) {
            throw new InvalidArgumentException('No se pudo guardar la imagen en el servidor.');
        }
        return $nombre;
    }

    public function eliminar(): void
    {
        $this->exigirAdmin();
        if (!$this->esPost()) {
            $this->redirigir();
        }

        $id = (int) ($_POST['id'] ?? 0);
        $servicio = $this->servicios->buscarPorId($id);
        if (!$servicio) {
            $this->redirigir();
        }

        try {
            $this->servicios->eliminar($id);
            $ruta = __DIR__ . '/../uploads/' . $servicio['imagen'];
            if ($servicio['imagen'] && is_file($ruta)) {
                unlink($ruta);
            }
            $this->redirigir('', 'Servicio eliminado.');
        } catch (PDOException $ex) {
            // 23503: la llave foránea impide borrar un servicio que ya tiene citas
            if ($ex->getCode() !== '23503') {
                throw $ex;   // cualquier otro error lo maneja index.php
            }
            $this->servicios->desactivar($id);
            $this->redirigir('', 'El servicio tiene citas registradas, así que se desactivó en lugar de borrarse.');
        }
    }
}
