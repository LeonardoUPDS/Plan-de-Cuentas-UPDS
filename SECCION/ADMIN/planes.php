<?php
require_once __DIR__ . '/check_admin.php';
requireAdmin();
require_once __DIR__ . '/../../TEMPLATE/header.php';

$stmt = $conexion->query('SELECT idPlan, Nombre, MaxSesiones, Precio, Estado FROM Plan ORDER BY idPlan');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container my-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span>Planes</span>
      <a class="btn btn-primary btn-sm" href="crear_plan.php">Crear Plan</a>
    </div>
    <div class="card-body">
      <table class="table table-hover">
        <thead><tr><th>Nombre</th><th>Max Sesiones</th><th>Precio</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['Nombre']) ?></td>
              <td><?= htmlspecialchars($r['MaxSesiones']) ?></td>
              <td><?= htmlspecialchars($r['Precio']) ?></td>
              <td><?= $r['Estado'] ? 'Activo' : 'Inactivo' ?></td>
              <td><a class="btn btn-sm btn-warning" href="editar_plan.php?id=<?= urlencode($r['idPlan']) ?>">Editar</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>