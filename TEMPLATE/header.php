<?php
require_once __DIR__ . '/../BD/conexion.php';
if (session_status() === PHP_SESSION_NONE) session_start();

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
              <?php if (tieneRol(2) || tieneRol(3)): ?>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL?>/SECCION/USUARIO/index.php">Usuarios</a></li>
              <?php endif; ?>

              <?php if (tieneRol(1) || tieneRol(2) || tieneRol(3)): ?>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL?>/SECCION/CURSOS/index.php">Cursos</a></li>
              <?php endif; ?>

              <?php if (tieneRol(3)): // Administrador ?>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL?>/SECCION/ROL/index.php">Rol</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL?>/SECCION/PERMISO/index.php">Permiso</a></li>
              <?php endif; ?>
            <?php else: ?>
              <li class="nav-item"><a class="nav-link" href="<?= BASE_URL?>/index.php">Inicio</a></li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>