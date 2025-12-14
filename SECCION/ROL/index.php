<?php
require_once __DIR__ . '/../../BD/conexion.php';
require_once __DIR__ . '/../../TEMPLATE/header.php';

$stmt = $conexion->query('SELECT idRol, Descripcion, Estado FROM rol');
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container my-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span>Listado de Roles</span>
      <a class="btn btn-primary btn-sm" href="crear.php">Crear Rol</a>
    </div>
    <div class="card-body">
      <table class="table table-bordered">
        <thead>
          <tr><th>ID</th><th>Descripción</th><th>Estado</th><th>Acciones</th></tr>
        </thead>
        <tbody>
          <?php if (count($roles) > 0): ?>
            <?php foreach ($roles as $r): ?>
              <tr>
                <td><?= htmlspecialchars($r['idRol']) ?></td>
                <td><?= htmlspecialchars($r['Descripcion']) ?></td>
                <td><?= $r['Estado'] == 1 ? 'Activo' : 'Inactivo' ?></td>
                <td>
                  <a class="btn btn-warning btn-sm" href="editar.php?id=<?= urlencode($r['idRol']) ?>">Editar</a>
                  <?php if ($r['Estado'] == 1): ?>
                    <a class="btn btn-danger btn-sm" href="toggle_estado.php?id=<?= urlencode($r['idRol']) ?>" onclick="return confirm('Desactivar rol?')">Desactivar</a>
                  <?php else: ?>
                    <a class="btn btn-success btn-sm" href="toggle_estado.php?id=<?= urlencode($r['idRol']) ?>" onclick="return confirm('Activar rol?')">Activar</a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="4" class="text-center">No hay roles</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>
