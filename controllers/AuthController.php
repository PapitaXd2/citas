<?php
class AuthController extends Controller
{
    private Usuario $usuarios;

    public function __construct(PDO $db)
    {
        $this->usuarios = new Usuario($db);
    }

    public function login(): void
    {
        $error = null;
        $correoRecordado = $_COOKIE['correo_recordado'] ?? '';

        if ($this->esPost()) {
            $correo = trim($_POST['correo'] ?? '');
            $contrasena = $_POST['contrasena'] ?? '';
            $usuario = $this->usuarios->buscarPorCorreo($correo);

            if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
                session_regenerate_id(true);
                $_SESSION['usuario'] = [
                    'id' => (int) $usuario['id'],
                    'nombre' => $usuario['nombre'],
                    'rol' => $usuario['rol'],
                ];

                // Cookie: recordar el correo 30 días, o borrarla
                if (isset($_POST['recordar'])) {
                    setcookie('correo_recordado', $correo, time() + 60 * 60 * 24 * 30, '/');
                } else {
                    setcookie('correo_recordado', '', time() - 3600, '/');
                }
                $this->redirigir();
            }
            $error = 'Correo o contraseña incorrectos.';
            $correoRecordado = $correo;
        }
        require __DIR__ . '/../views/auth/login.php';
    }

    public function registro(): void
    {
        $error = null;
        $nombre = $correo = $telefono = '';

        if ($this->esPost()) {
            $nombre = trim($_POST['nombre'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $contrasena = $_POST['contrasena'] ?? '';

            // Validación en el servidor con expresiones regulares
            if (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]{3,80}$/u', $nombre)) {
                $error = 'El nombre solo puede tener letras y espacios (mínimo 3).';
            } elseif (!preg_match('/^[\w.+-]+@[\w-]+(\.[\w-]+)+$/', $correo)) {
                $error = 'Escribe un correo válido, por ejemplo ana@correo.com.';
            } elseif (!preg_match('/^\d{10}$/', $telefono)) {
                $error = 'El teléfono debe tener 10 dígitos, sin espacios ni guiones.';
            } elseif (strlen($contrasena) < 8) {
                $error = 'La contraseña debe tener al menos 8 caracteres.';
            } elseif ($this->usuarios->buscarPorCorreo($correo)) {
                $error = 'Ese correo ya está registrado.';
            } else {
                $this->usuarios->crear($nombre, $correo, $telefono, $contrasena);
                $this->redirigir('login', 'Cuenta creada. Ya puedes iniciar sesión.');
            }
        }
        require __DIR__ . '/../views/auth/registro.php';
    }

    public function salir(): void
    {
        $_SESSION = [];
        session_destroy();
        header('Location: index.php?accion=login');
        exit;
    }
}