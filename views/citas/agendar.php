<?php require __DIR__ . '/../layout/header.php'; ?>
<section class="row justify-content-center">
  <div class="col-lg-7">
    <h1 class="h3 mb-3">Agendar una cita</h1>

    <?php if (!$servicios): ?>
      <p class="text-muted">No hay servicios disponibles por ahora.</p>
    <?php else: ?>
    <form method="post" action="index.php?accion=agendar" class="card card-body tarjeta">
      <div class="mb-3">
        <label for="servicio_id" class="form-label">Servicio</label>
        <select id="servicio_id" name="servicio_id" class="form-select" required>
          <option value="">Elige un servicio</option>
          <?php foreach ($servicios as $servicio): ?>
            <option value="<?= $servicio['id'] ?>" <?= $datos['servicio_id'] === (int) $servicio['id'] ? 'selected' : '' ?>>
              <?= e($servicio['nombre']) ?> · <?= $servicio['duracion'] ?> min · $<?= number_format((float) $servicio['precio'], 2) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="fecha" class="form-label">Fecha</label>
          <input type="date" id="fecha" name="fecha" class="form-control"
                 min="<?= $hoy ?>" max="<?= $limite ?>" value="<?= e($datos['fecha']) ?>" required>
          <div class="form-text">De lunes a sábado.</div>
        </div>
        <div class="col-md-6 mb-3">
          <label for="hora" class="form-label">Hora</label>
          <select id="hora" name="hora" class="form-select" data-seleccionada="<?= e($datos['hora']) ?>" required disabled>
            <option value="">Primero elige la fecha</option>
          </select>
          <div id="mensaje-horas" class="form-text" aria-live="polite"></div>
        </div>
      </div>

      <div class="mb-3">
        <label for="notas" class="form-label">Notas para el barbero (opcional)</label>
        <textarea id="notas" name="notas" class="form-control" rows="3" maxlength="300"><?= e($datos['notas']) ?></textarea>
        <div id="contador" class="form-text text-end"></div>
      </div>

      <button class="btn btn-primary">Agendar cita</button>
    </form>
    <?php endif; ?>
  </div>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>