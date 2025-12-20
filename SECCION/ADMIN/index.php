<?php
require_once __DIR__ . '/check_admin.php';
requireAdmin();
require_once __DIR__ . '/../../TEMPLATE/header.php';

// PÁGINA PRINCIPAL DEL PANEL DE CONTROL - Resumen rápido
?>
<div class="container my-4">
  <div class="row">
    <div class="col-md-3">
      <div class="list-group">
        <a href="index.php" class="list-group-item list-group-item-action active">Panel</a>
        <a href="usuarios.php" class="list-group-item list-group-item-action">Usuarios</a>
        <a href="roles.php" class="list-group-item list-group-item-action">Roles</a>
        <a href="permisos.php" class="list-group-item list-group-item-action">Permisos</a>
        <a href="planes.php" class="list-group-item list-group-item-action">Planes</a>
        <a href="suscripciones.php" class="list-group-item list-group-item-action">Suscripciones</a>
        <a href="sesiones.php" class="list-group-item list-group-item-action">Sesiones</a>
        <a href="cursos.php" class="list-group-item list-group-item-action">Cursos</a>
      </div>
    </div>
    <div class="col-md-9">
      <div class="card">
        <div class="card-body">
          <h3>Panel de Control (Administrador)</h3>
          <p>Resumen de recursos y accesos rápidos. Aquí verás métricas y accesos a cada recurso.</p>

          <div class="row">
            <div class="col-md-4">
              <div class="card mb-3">
                <div class="card-body">
                  <h5 class="card-title">Usuarios</h5>
                  <p class="card-text">Gestiona usuarios registrados.</p>
                  <a href="usuarios.php" class="btn btn-primary btn-sm">Ir a Usuarios</a>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card mb-3">
                <div class="card-body">
                  <h5 class="card-title">Planes</h5>
                  <p class="card-text">Gestiona planes y límites de sesión.</p>
                  <a href="planes.php" class="btn btn-primary btn-sm">Ir a Planes</a>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card mb-3">
                <div class="card-body">
                  <h5 class="card-title">Sesiones</h5>
                  <p class="card-text">Visualiza sesiones activas por usuario.</p>
                  <a href="sesiones.php" class="btn btn-primary btn-sm">Ir a Sesiones</a>
                </div>
              </div>
            </div>
          </div>

          <!-- Aquí puedes añadir widgets/estadísticas: total usuarios, activos, planes por usuario, etc. -->

        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>