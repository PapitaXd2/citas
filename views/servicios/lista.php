<?php require __DIR__ . '/../layout/header.php'; ?>
<h1 class="h3 mb-4">Nuestros servicios</h1>

<section class="row g-4">
  <?php if (!$servicios): ?>
    <p class="text-muted">Todavía no hay servicios disponibles.</p>
  <?php endif; ?>

  <?php foreach ($servicios as $servicio): ?>
    <article class="col-sm-6 col-lg-4">
      <div class="card h-100 tarjeta">
        <?php if ($servicio['imagen']): ?>
          <img src="uploads/<?= e($servicio['imagen']) ?>" class="card-img-top imagen-servicio"
               alt="<?= e($servicio['nombre']) ?>">
        <?php else: ?>
          <div class="imagen-servicio sin-imagen">Sin imagen</div>
        <?php endif; ?>

        <div class="card-body">
          <h2 class="h5 card-title"><?= e($servicio['nombre']) ?></h2>
          <p class="card-text"><?= e($servicio['descripcion']) ?></p>
          <p class="mb-0">
            <span class="badge text-bg-light"><?= $servicio['duracion'] ?> min</span>
            <strong class="precio">$<?= number_format((float) $servicio['precio'], 2) ?></strong>
            <?php if (!$servicio['activo']): ?>
              <span class="badge text-bg-secondary">Inactivo</span>
            <?php endif; ?>
          </p>
        </div>

        <div class="card-footer bg-transparent d-flex gap-2">
          <?php if ($esAdmin): ?>
            <a href="index.php?accion=editar&id=<?= $servicio['id'] ?>" class="btn btn-outline-primary btn-sm">Editar</a>
            <form method="post" action="index.php?accion=eliminar" class="confirmar"
                  data-mensaje="¿Eliminar este servicio?">
              <input type="hidden" name="id" value="<?= $servicio['id'] ?>">
              <button class="btn btn-outline-danger btn-sm">Eliminar</button>
            </form>
          <?php else: ?>
            <a href="index.php?accion=agendar&servicio=<?= $servicio['id'] ?>" class="btn btn-primary btn-sm">Agendar</a>
          <?php endif; ?>
        </div>
      </div>
    </article>
  <?php endforeach; ?>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>