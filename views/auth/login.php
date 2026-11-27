<?php require __DIR__ . '/../layout/header.php'; ?>
<section class="row justify-content-center">
  <div class="col-md-6 col-lg-4">
    <h1 class="h3 mb-3">Iniciar sesión</h1>
    <form method="post" action="index.php?accion=login" class="card card-body tarjeta">
      <div class="mb-3">
        <label for="correo" class="form-label">Correo</label>
        <input type="email" id="correo" name="correo" class="form-control"value="<?= e($correoRecordado) ?>" required>
      </div>
      <div class="mb-3">
        <label for="contrasena" class="form-label">Contraseña</label>
        <input type="password" id="contrasena" name="contrasena" class="form-control" required>
      </div>
      <div class="form-check mb-3">
        <input type="checkbox" id="recordar" name="recordar" class="form-check-input" <?= $correoRecordado ? 'checked' : '' ?>>
        <label for="recordar" class="form-check-label">Recordar mi correo</label>
      </div>
      <button class="btn btn-primary w-100">Entrar</button>
      <p class="mt-3 mb-0 text-center">¿No tienes cuenta? <a href="index.php?accion=registro">Regístrate</a></p>
    </form>
  </div>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>