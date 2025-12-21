<?php
require_once __DIR__ . '/check_admin.php';
requireAdmin();
require_once __DIR__ . '/../../TEMPLATE/header.php';

$stmt = $conexion->query('SELECT u.idUsuario, u.Correo, u.EmailVerificado, u.Estado, r.Descripcion AS Rol FROM usuario u LEFT JOIN rol r ON u.idRol = r.idRol ORDER BY u.idUsuario DESC');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container my-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div><span>Usuarios</span></div>
      <div>
        <a class="btn btn-secondary btn-sm me-2" href="<?= BASE_URL?>/SECCION/ADMIN/index.php">Volver</a>
        <a class="btn btn-primary btn-sm" href="crear_usuario.php">Crear Usuario</a>
      </div>
    </div>
    <div class="card-body">
      <?php if (!empty($_GET['success'])): ?><div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div><?php endif; ?>
      <?php if (!empty($_GET['error'])): ?><div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div><?php endif; ?>
      <table class="table table-striped">
        <thead><tr><th>Correo</th><th>Rol</th><th>Verificado</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
          <?php foreach ($users as $u): ?>
            <tr>
              <td><?= htmlspecialchars($u['Correo']) ?></td>
              <td><?= htmlspecialchars($u['Rol'] ?? 'Usuario') ?></td>
              <td><?= $u['EmailVerificado'] ? 'Sí' : 'No' ?></td>
              <td><?= $u['Estado'] ? 'Activo' : 'Inactivo' ?></td>
              <td>
                <a class="btn btn-sm btn-info" href="editar_usuario.php?id=<?= urlencode($u['idUsuario']) ?>">Editar</a>

                <form method="POST" action="<?= BASE_URL?>/SECCION/ADMIN/toggle_estado.php" style="display:inline">
                  <input type="hidden" name="table" value="Usuario">
                  <input type="hidden" name="id" value="<?= (int)$u['idUsuario'] ?>">
                  <input type="hidden" name="back" value="<?= BASE_URL?>/SECCION/ADMIN/usuarios.php">
                  <button type="submit" class="btn btn-sm <?= $u['Estado'] ? 'btn-danger' : 'btn-success' ?>" onclick="return confirm('¿Estás seguro de cambiar el estado del usuario?')"><?= $u['Estado'] ? 'Desactivar' : 'Activar' ?></button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <!-- Comentario: Aquí puede agregar filtros de búsqueda, paginación, exportación de datos, etc. -->

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>