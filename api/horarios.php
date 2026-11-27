<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Cita.php';

header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['usuario'])) {
    http_response_code(401);
    echo json_encode(['horas' => [], 'mensaje' => 'Inicia sesión']);
    exit;
}

$fecha = $_GET['fecha'] ?? '';
if (!Cita::fechaValida($fecha)) {
    echo json_encode(['horas' => [], 'mensaje' => 'Elige un día de lunes a sábado dentro de los próximos 30 días.']);
    exit;
}

try {
    $horas = (new Cita(conectar()))->horasDisponibles($fecha);
    echo json_encode([
        'horas' => $horas,
        'mensaje' => $horas ? count($horas) . ' horarios disponibles' : 'Ya no hay horarios libres ese día.',
    ]);
} catch (PDOException $ex) {
    error_log($ex->getMessage());
    http_response_code(500);
    echo json_encode(['horas' => [], 'mensaje' => 'No se pudieron cargar los horarios.']);
}
