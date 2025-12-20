<?php
require_once __DIR__ . '/check_admin.php';
requireAdmin();
require_once __DIR__ . '/../../TEMPLATE/header.php';

$stmt = $conexion->query('SELECT idUsuario, Correo, idRol, EmailVerificado, Estado FROM usuario ORDER BY idUsuario DESC');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container my-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span>Usuarios</span>
      <a class="btn btn-primary btn-sm" href="crear_usuario.php">Crear Usuario</a>
    </div>
    <div class="card-body">
      <table class="table table-striped">
        <thead><tr><th>Correo</th><th>Rol</th><th>Verificado</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
          <?php foreach ($users as $u): ?>
            <tr>
              <td><?= htmlspecialchars($u['Correo']) ?></td>
              <td><?= htmlspecialchars($u['idRol']) ?></td>
              <td><?= $u['EmailVerificado'] ? 'Sí' : 'No' ?></td>
              <td><?= $u['Estado'] ? 'Activo' : 'Inactivo' ?></td>
              <td>
                <a class="btn btn-sm btn-info" href="editar_usuario.php?id=<?= urlencode($u['idUsuario']) ?>">Editar</a>
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