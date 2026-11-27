<?php require __DIR__ . '/../layout/header.php'; ?>
<h1 class="h3 mb-3">Agenda</h1>

<?php if (!$citas): ?>
  <p class="text-muted">No hay citas próximas.</p>
<?php else: ?>
  <div class="table-responsive tarjeta bg-white">
    <table class="table align-middle mb-0">
      <thead>
        <tr><th>Fecha</th><th>Hora</th><th>Cliente</th><th>Servicio</th><th>Notas</th><th>Estado</th><th><span class="visually-hidden">Acciones</span></th></tr>
      </thead>
      <tbody>
        <?php foreach ($citas as $cita): ?>
          <tr>
            <td><?= date('d/m/Y', strtotime($cita['fecha'])) ?></td>
            <td><?= e($cita['hora']) ?></td>
            <td><?= e($cita['cliente']) ?><br><small class="text-muted"><?= e($cita['telefono']) ?></small></td>
            <td><?= e($cita['servicio']) ?></td>
            <td><?= e($cita['notas']) ?></td>
            <td><?= badgeEstado($cita['estado']) ?></td>
            <td class="text-end text-nowrap">
              <?php if ($cita['estado'] === 'pendiente'): ?>
                <form method="post" action="index.php?accion=estado-cita" class="d-inline">
                  <input type="hidden" name="id" value="<?= $cita['id'] ?>">
                  <input type="hidden" name="estado" value="confirmada">
                  <button class="btn btn-success btn-sm">Confirmar</button>
                </form>
              <?php endif; ?>
              <?php if ($cita['estado'] !== 'cancelada'): ?>
                <form method="post" action="index.php?accion=estado-cita" class="d-inline confirmar"
                      data-mensaje="¿Cancelar la cita de <?= e($cita['cliente']) ?>?">
                  <input type="hidden" name="id" value="<?= $cita['id'] ?>">
                  <input type="hidden" name="estado" value="cancelada">
                  <button class="btn btn-outline-danger btn-sm">Cancelar</button>
                </form>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
<?php require __DIR__ . '/../layout/footer.php'; ?>