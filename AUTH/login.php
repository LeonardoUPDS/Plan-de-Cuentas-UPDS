<?php
if (session_status() === PHP_SESSION_NONE) session_start();
echo "Si ya está logueado, redirigir al inicio";
if (!empty($_SESSION['idUsuario'])) {
    header("Location: ../index.php");
    exit;
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Iniciar sesión</title>
    <style>
      body { background: #f8f9fa; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
      .card { width: 100%; max-width: 420px; }
    </style>
  </head>
  <body>
    <div class="card shadow-sm">
      <div class="card-header text-center">Iniciar sesión</div>
      <div class="card-body">
        <?php if (!empty($_GET['error'])): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <form method="POST" action="login_process.php">
          <div class="mb-3">
            <label class="form-label">Correo</label>
            <input type="email" name="correo" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="contrasena" class="form-control" required>
          </div>
          <div class="d-grid">
            <button class="btn btn-primary" type="submit">Entrar</button>
          </div>
          <div class="d-grid">
            <a href="register.php" class="btn btn-secondary">Registrarse</a>
          </div>
          <div class="d-grid">
            <a href="recuperar_password.php" class="btn btn-secondary">Recuperar contraseña</a>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
