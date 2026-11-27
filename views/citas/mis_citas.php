<?php require __DIR__ . '/../layout/header.php'; ?>
<h1 class="h3 mb-3">Mis citas</h1>

<?php if (!$citas): ?>
  <p class="text-muted">Aún no tienes citas. <a href="index.php?accion=agendar">Agenda una</a>.</p>
<?php else: ?>
  <div class="table-responsive tarjeta bg-white">
    <table class="table align-middle mb-0">
      <thead>
        <tr><th>Fecha</th><th>Hora</th><th>Servicio</th><th>Precio</th><th>Estado</th><th><span class="visually-hidden">Acciones</span></th></tr>
      </thead>
      <tbody>
        <?php foreach ($citas as $cita): ?>
          <tr>
            <td><?= date('d/m/Y', strtotime($cita['fecha'])) ?></td>
            <td><?= e($cita['hora']) ?></td>
            <td><?= e($cita['servicio']) ?></td>
            <td>$<?= number_format((float) $cita['precio'], 2) ?></td>
            <td><?= badgeEstado($cita['estado']) ?></td>
            <td class="text-end">
              <?php if ($cita['estado'] !== 'cancelada' && $cita['fecha'] >= $hoy): ?>
                <form method="post" action="index.php?accion=cancelar-cita" class="confirmar"
                      data-mensaje="¿Cancelar esta cita?">
                  <input type="hidden" name="id" value="<?= $cita['id'] ?>">
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