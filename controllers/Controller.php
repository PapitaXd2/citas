<?php
abstract class Controller
{
    // Si no hay sesión, manda al inicio de sesión
    protected function exigirSesion(): array
    {
        if (empty($_SESSION['usuario'])) {
            header('Location: index.php?accion=login');
            exit;
        }
        return $_SESSION['usuario'];
    }

    // Solo deja pasar al administrador
    protected function exigirAdmin(): array
    {
        $usuario = $this->exigirSesion();
        if ($usuario['rol'] !== 'admin') {
            http_response_code(403);
            $error = 'Solo el administrador puede hacer esto.';
            require __DIR__ . '/../views/error.php';
            exit;
        }
        return $usuario;
    }

    // Redirige a una acción con un aviso opcional
    protected function redirigir(string $accion = '', string $aviso = ''): void
    {
        if ($aviso) {
            $_SESSION['aviso'] = $aviso;
        }
        header('Location: index.php' . ($accion ? '?accion=' . $accion : ''));
        exit;
    }

    protected function esPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}
