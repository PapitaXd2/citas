<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Barbería · Citas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/estilos.css" rel="stylesheet">
</head>
<body>
<header>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="index.php">Barbería · Citas</a>
      <?php if (!empty($_SESSION['usuario'])): ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu"
                aria-controls="menu" aria-expanded="false" aria-label="Abrir menú">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
          <ul class="navbar-nav me-auto">
            <li class="nav-item"><a class="nav-link" href="index.php">Servicios</a></li>
            <?php if ($_SESSION['usuario']['rol'] === 'admin'): ?>
              <li class="nav-item"><a class="nav-link" href="index.php?accion=agenda">Agenda</a></li>
              <li class="nav-item"><a class="nav-link" href="index.php?accion=nuevo">Agregar servicio</a></li>
            <?php else: ?>
              <li class="nav-item"><a class="nav-link" href="index.php?accion=agendar">Agendar cita</a></li>
              <li class="nav-item"><a class="nav-link" href="index.php?accion=mis-citas">Mis citas</a></li>
            <?php endif; ?>
          </ul>
          <span class="navbar-text me-3">Hola, <?= e($_SESSION['usuario']['nombre']) ?></span>
          <a class="btn btn-outline-light btn-sm" href="index.php?accion=salir">Cerrar sesión</a>
        </div>
      <?php endif; ?>
    </div>
  </nav>
</header>
<main class="container py-4">
  <?php if (!empty($_SESSION['aviso'])): ?>
    <div class="alert alert-success aviso" role="status"><?= e($_SESSION['aviso']) ?></div>
    <?php unset($_SESSION['aviso']); ?>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="alert alert-danger" role="alert"><?= e($error) ?></div>
  <?php endif; ?>