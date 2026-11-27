<?php require __DIR__ . '/../layout/header.php'; ?>
<?php $esNuevo = empty($servicio['id']); ?>
<section class="row justify-content-center">
  <div class="col-lg-7">
    <h1 class="h3 mb-3"><?= $esNuevo ? 'Agregar servicio' : 'Editar servicio' ?></h1>
    <form method="post" action="index.php?accion=guardar" enctype="multipart/form-data" class="card card-body tarjeta">
      <input type="hidden" name="id" value="<?= (int) ($servicio['id'] ?? 0) ?>">

      <div class="mb-3">
        <label for="nombre" class="form-label">Nombre del servicio</label>
        <input type="text" id="nombre" name="nombre" class="form-control" minlength="3" maxlength="80"
               value="<?= e($servicio['nombre'] ?? '') ?>" required>
      </div>
      <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea id="descripcion" name="descripcion" class="form-control" rows="3"
                  maxlength="300"><?= e($servicio['descripcion'] ?? '') ?></textarea>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="duracion" class="form-label">Duración (minutos)</label>
          <input type="number" id="duracion" name="duracion" class="form-control" min="10" max="240" step="5"
                 value="<?= e((string) ($servicio['duracion'] ?? 30)) ?>" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="precio" class="form-label">Precio (MXN)</label>
          <input type="number" id="precio" name="precio" class="form-control" min="0" step="0.01"
                 value="<?= e((string) ($servicio['precio'] ?? '')) ?>" required>
        </div>
      </div>

      <div class="mb-3">
        <label for="imagen" class="form-label">Imagen (JPG, PNG o WEBP, máximo 2 MB)</label>
        <input type="file" id="imagen" name="imagen" class="form-control" accept="image/jpeg,image/png,image/webp">
        <img id="vista-previa" alt="Vista previa de la imagen"
             class="mt-2 vista-previa <?= empty($servicio['imagen']) ? 'd-none' : '' ?>"
             src="<?= empty($servicio['imagen']) ? '' : 'uploads/' . e($servicio['imagen']) ?>">
      </div>

      <div class="form-check mb-3">
        <input type="checkbox" id="activo" name="activo" class="form-check-input"
               <?= ($servicio['activo'] ?? true) ? 'checked' : '' ?>>
        <label for="activo" class="form-check-label">Mostrar a los clientes</label>
      </div>

      <div class="d-flex gap-2">
        <button class="btn btn-primary">Guardar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
      </div>
    </form>
  </div>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>