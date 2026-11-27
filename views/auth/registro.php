<?php require __DIR__ . '/../layout/header.php'; ?>
<section class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    <h1 class="h3 mb-3">Crear cuenta</h1>
    <form method="post" action="index.php?accion=registro" class="card card-body tarjeta">
      <div class="mb-3">
        <label for="nombre" class="form-label">Nombre completo</label>
        <input type="text" id="nombre" name="nombre" class="form-control" value="<?= e($nombre) ?>"
               pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]{3,80}" title="Solo letras y espacios, mínimo 3" required>
      </div>
      <div class="mb-3">
        <label for="correo" class="form-label">Correo</label>
        <input type="email" id="correo" name="correo" class="form-control" value="<?= e($correo) ?>" required>
      </div>
      <div class="mb-3">
        <label for="telefono" class="form-label">Teléfono (10 dígitos)</label>
        <input type="tel" id="telefono" name="telefono" class="form-control" value="<?= e($telefono) ?>"
               pattern="[0-9]{10}" title="10 dígitos, sin espacios" inputmode="numeric" required>
      </div>
      <div class="mb-3">
        <label for="contrasena" class="form-label">Contraseña (mínimo 8 caracteres)</label>
        <input type="password" id="contrasena" name="contrasena" class="form-control" minlength="8" required>
      </div>
      <div class="mb-3">
        <label for="confirmar" class="form-label">Confirma tu contraseña</label>
        <input type="password" id="confirmar" class="form-control" minlength="8" required>
      </div>
      <button class="btn btn-primary w-100">Registrarme</button>
      <p class="mt-3 mb-0 text-center">¿Ya tienes cuenta? <a href="index.php?accion=login">Inicia sesión</a></p>
    </form>
  </div>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>