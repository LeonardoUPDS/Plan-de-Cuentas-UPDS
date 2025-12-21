<?php
require_once __DIR__ . '/check_admin.php';
requireAdmin();
require_once __DIR__ . '/../../TEMPLATE/header.php';

$stmt = $conexion->query('SELECT idRol, Descripcion, Estado FROM rol ORDER BY idRol');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container my-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div><span>Roles</span></div>
      <div>
        <a class="btn btn-secondary btn-sm me-2" href="<?= BASE_URL?>/SECCION/ADMIN/index.php">Volver</a>
        <a class="btn btn-primary btn-sm" href="crear_rol.php">Crear Rol</a>
      </div>
    </div>
    <div class="card-body">
      <table class="table table-bordered">
        <thead><tr><th>ID</th><th>Descripción</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['idRol']) ?></td>
              <td><?= htmlspecialchars($r['Descripcion']) ?></td>
              <td><?= $r['Estado'] ? 'Activo' : 'Inactivo' ?></td>
              <td>
                <a class="btn btn-sm btn-warning" href="editar_rol.php?id=<?= urlencode($r['idRol']) ?>">Editar</a>
                <form method="POST" action="<?= BASE_URL?>/SECCION/ADMIN/toggle_estado.php" style="display:inline">
                  <input type="hidden" name="table" value="Rol">
                  <input type="hidden" name="id" value="<?= (int)$r['idRol'] ?>">
                  <input type="hidden" name="back" value="<?= BASE_URL?>/SECCION/ADMIN/roles.php">
                  <button type="submit" class="btn btn-sm <?= $r['Estado'] ? 'btn-danger' : 'btn-success' ?>" onclick="return confirm('¿Cambiar estado del rol?')"><?= $r['Estado'] ? 'Desactivar' : 'Activar' ?></button>
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