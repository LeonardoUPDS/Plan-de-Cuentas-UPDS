<?php
require_once __DIR__ . '/check_admin.php';
requireAdmin();
require_once __DIR__ . '/../../TEMPLATE/header.php';

$stmt = $conexion->query('SELECT permiso_id, descripcion, Estado FROM Permiso ORDER BY permiso_id');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container my-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span>Permisos</span>
      <a class="btn btn-primary btn-sm" href="crear_permiso.php">Crear Permiso</a>
    </div>
    <div class="card-body">
      <table class="table table-bordered">
        <thead><tr><th>ID</th><th>Descripción</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['permiso_id']) ?></td>
              <td><?= htmlspecialchars($r['descripcion']) ?></td>
              <td><?= $r['Estado'] ? 'Activo' : 'Inactivo' ?></td>
              <td><a class="btn btn-sm btn-warning" href="editar_permiso.php?id=<?= urlencode($r['permiso_id']) ?>">Editar</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>