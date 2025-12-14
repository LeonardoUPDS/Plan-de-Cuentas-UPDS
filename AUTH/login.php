<?php
// Página de login: no incluir el header para una pantalla de inicio de sesión limpia
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>Iniciar Sesión</title>
    </head>
    <body style="background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); min-height:100vh; display:flex; align-items:center; justify-content:center;">
        <div class="card p-4" style="width:320px;">
            <h4 class="mb-3 text-center">Iniciar Sesión</h4>
            <form method="POST" action="login_process.php">
                <div class="mb-2">
                    <input type="email" class="form-control" name="correo" placeholder="Correo" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="contrasena" placeholder="Contraseña" required>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-primary" type="submit">Iniciar sesión</button>
                </div>
            </form>
            <div class="mt-3 text-center">
                <a href="register.php">Registrarse</a> · <a href="recuperar_password.php">¿Olvidaste tu contraseña?</a>
            </div>
        </div>
    </body>
</html>
