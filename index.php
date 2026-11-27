<?php
session_start();

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/models/Usuario.php';
require_once __DIR__ . '/models/Servicio.php';
require_once __DIR__ . '/models/Cita.php';
require_once __DIR__ . '/controllers/Controller.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/ServicioController.php';
require_once __DIR__ . '/controllers/CitaController.php';

// Escapa texto antes de mostrarlo en HTML (evita XSS)
function e(?string $texto): string
{
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}

// Etiqueta de color según el estado de la cita
function badgeEstado(string $estado): string
{
    $clases = [
        'pendiente' => 'text-bg-warning',
        'confirmada' => 'text-bg-success',
        'cancelada' => 'text-bg-secondary',
    ];
    return '<span class="badge ' . ($clases[$estado] ?? 'text-bg-light') . '">' . e(ucfirst($estado)) . '</span>';
}

$accion = $_GET['accion'] ?? 'servicios';

try {
    $db = conectar();
    $auth = new AuthController($db);
    $servicios = new ServicioController($db);
    $citas = new CitaController($db);

    match ($accion) {
        'login'         => $auth->login(),
        'registro'      => $auth->registro(),
        'salir'         => $auth->salir(),
        'nuevo'         => $servicios->formulario(),
        'editar'        => $servicios->formulario((int) ($_GET['id'] ?? 0)),
        'guardar'       => $servicios->guardar(),
        'eliminar'      => $servicios->eliminar(),
        'agendar'       => $citas->agendar(),
        'mis-citas'     => $citas->misCitas(),
        'cancelar-cita' => $citas->cancelar(),
        'agenda'        => $citas->agenda(),
        'estado-cita'   => $citas->cambiarEstado(),
        default         => $servicios->catalogo(),
    };
} catch (PDOException $ex) {
    error_log($ex->getMessage());   // el detalle queda en el log de Apache
    $error = 'No pudimos conectar con la base de datos. Intenta más tarde.';
    require __DIR__ . '/views/error.php';
}
