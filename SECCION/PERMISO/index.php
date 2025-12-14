<?php
require_once __DIR__ . '/../../BD/conexion.php';
require_once __DIR__ . '/../../TEMPLATE/header.php';

$stmt = $conexion->query('SELECT permiso_id, descripcion, Estado FROM permiso');
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container my-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span>Listado de Permisos</span>
      <a class="btn btn-primary btn-sm" href="crear.php">Crear Permiso</a>
    </div>
    <div class="card-body">
      <table class="table table-bordered">
        <thead>
          <tr><th>ID</th><th>Descripción</th><th>Estado</th><th>Acciones</th></tr>
        </thead>
        <tbody>
          <?php if (count($items) > 0): ?>
            <?php foreach ($items as $it): ?>
              <tr>
                <td><?= htmlspecialchars($it['permiso_id']) ?></td>
                <td><?= htmlspecialchars($it['descripcion']) ?></td>
                <td><?= $it['Estado'] == 1 ? 'Activo' : 'Inactivo' ?></td>
                <td>
                  <a class="btn btn-warning btn-sm" href="editar.php?id=<?= urlencode($it['permiso_id']) ?>">Editar</a>
                  <?php if ($it['Estado'] == 1): ?>
                    <a class="btn btn-danger btn-sm" href="toggle_estado.php?id=<?= urlencode($it['permiso_id']) ?>" onclick="return confirm('Desactivar permiso?')">Desactivar</a>
                  <?php else: ?>
                    <a class="btn btn-success btn-sm" href="toggle_estado.php?id=<?= urlencode($it['permiso_id']) ?>" onclick="return confirm('Activar permiso?')">Activar</a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="4" class="text-center">No hay permisos</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>
