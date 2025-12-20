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
      <div><span>Planes</span></div>
      <div>
        <a class="btn btn-secondary btn-sm me-2" href="<?= BASE_URL?>/SECCION/ADMIN/index.php">Volver</a>
        <a class="btn btn-primary btn-sm" href="crear_plan.php">Crear Plan</a>
      </div>
    </div>
    <div class="card-body">
      <?php if (!empty($_GET['success'])): ?><div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div><?php endif; ?>
      <?php if (!empty($_GET['error'])): ?><div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div><?php endif; ?>
      <table class="table table-hover">
        <thead><tr><th>Nombre</th><th>Max Sesiones</th><th>Precio</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['Nombre']) ?></td>
              <td><?= htmlspecialchars($r['MaxSesiones']) ?></td>
              <td><?= htmlspecialchars($r['Precio']) ?></td>
              <td><?= $r['Estado'] ? 'Activo' : 'Inactivo' ?></td>
              <td>
                <a class="btn btn-sm btn-warning" href="editar_plan.php?id=<?= urlencode($r['idPlan']) ?>">Editar</a>
                <form method="POST" action="<?= BASE_URL?>/SECCION/ADMIN/toggle_estado.php" style="display:inline">
                  <input type="hidden" name="table" value="Plan">
                  <input type="hidden" name="id" value="<?= (int)$r['idPlan'] ?>">
                  <input type="hidden" name="back" value="<?= BASE_URL?>/SECCION/ADMIN/planes.php">
                  <button type="submit" class="btn btn-sm <?= $r['Estado'] ? 'btn-danger' : 'btn-success' ?>" onclick="return confirm('¿Cambiar estado del plan?')"><?= $r['Estado'] ? 'Desactivar' : 'Activar' ?></button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>