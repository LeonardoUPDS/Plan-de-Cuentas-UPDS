<?php
require_once __DIR__ . '/../BD/conexion.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// Verificar que la sesión local está permitida por la BD (solo si existe sesión)
require_once __DIR__ . '/../SECCION/SESION/verificar_sesion.php';

// Vincular verificadores de rol y permiso
require_once __DIR__ . '/../SECCION/ROL/verificar_rol.php';
require_once __DIR__ . '/../SECCION/PERMISO/verificar_permiso.php';

// calcular ruta base de la app (ej: /PlataformaEducativa)
$parts = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));
$appRoot = '/' . ($parts[0] ?? '');
?>

<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Plataforma Educativa</title>
    <style>
    /* Estilos simples sin gestión de temas */
    body { background: #f8f9fa; color: #212529; }
    .card { background: #fff; }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light mb-3 border-bottom">
      <div class="container-fluid">
        <a class="navbar-brand" href="<?= BASE_URL?>/index.php">Plataforma Educativa</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <?php if (isset($_SESSION['idUsuario'])): ?>
              <?php if (tieneRol(2)): ?>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL?>/SECCION/USUARIO/index.php">Usuarios</a></li>
              <?php endif; ?>

              <?php if (tieneRol(1) || tieneRol(2)): ?>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL?>/SECCION/CURSOS/cursoactivos.php">Cursos</a></li>
              <?php endif; ?>
              
            <?php else: ?>
              <li class="nav-item"><a class="nav-link" href="<?= BASE_URL?>/index.php">Inicio</a></li>
            <?php endif; ?>
          </ul>

          <?php if (isset($_SESSION['idUsuario'])): ?>
            <?php
              $stmtUser = $conexion->prepare("SELECT Correo FROM usuario WHERE idUsuario = :id");
              $stmtUser->bindParam(':id', $_SESSION['idUsuario'], PDO::PARAM_INT);
              $stmtUser->execute();
              $userInfo = $stmtUser->fetch(PDO::FETCH_ASSOC);
              $correoUser = $userInfo['Correo'] ?? 'Perfil';

              $stmtSub = $conexion->prepare("SELECT p.Nombre FROM suscripcion s JOIN plan p ON s.idPlan = p.idPlan WHERE s.idUsuario = :id AND s.Estado = 1 ORDER BY s.FechaInicio DESC LIMIT 1");
              $stmtSub->bindParam(':id', $_SESSION['idUsuario'], PDO::PARAM_INT);
              $stmtSub->execute();
              $plan = $stmtSub->fetch(PDO::FETCH_ASSOC);
            ?>

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <?= htmlspecialchars($correoUser) ?><?php if ($plan): ?> <small class="text-muted"> (<?= htmlspecialchars($plan['Nombre']) ?>)</small><?php endif; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                  <li><a class="dropdown-item" href="<?= BASE_URL?>/SECCION/USUARIO/perfil.php">Perfil</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="<?= BASE_URL?>/AUTH/logout.php">Cerrar sesión</a></li>
                </ul>
              </li>
            </ul>
          <?php endif; ?>
        </div>
      </div>
    </nav>