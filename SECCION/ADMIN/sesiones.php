<?php
require_once __DIR__ . '/check_admin.php';
requireAdmin();
require_once __DIR__ . '/../../TEMPLATE/header.php';

$stmt = $conexion->query('SELECT s.idSesion, u.Correo, s.TokenSesion, s.IP, s.Navegador, s.FechaInicio, s.UltimaActividad, s.Activa FROM sesionusuario s JOIN usuario u ON s.idUsuario = u.idUsuario ORDER BY s.FechaInicio DESC');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container my-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div><span>Sesiones</span></div>
      <div>
        <a class="btn btn-secondary btn-sm" href="<?= BASE_URL?>/SECCION/ADMIN/index.php">Volver</a>
      </div>
    </div>
    <div class="card-body">
      <table class="table table-sm">
        <thead><tr><th>Usuario</th><th>IP</th><th>Navegador</th><th>Fecha Inicio</th><th>Activa</th><th>Acciones</th></tr></thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['Correo']) ?></td>
              <td><?= htmlspecialchars($r['IP']) ?></td>
              <td><?= htmlspecialchars($r['Navegador']) ?></td>
              <td><?= htmlspecialchars($r['FechaInicio']) ?></td>
              <td><?= $r['Activa'] ? 'Sí' : 'No' ?></td>
              <td>
                <?php if ($r['Activa']): ?>
                  <a class="btn btn-sm btn-danger" href="revocar_sesion.php?id=<?= urlencode($r['idSesion']) ?>">Revocar</a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <!-- Comentario: añadir filtros y posibilidad de revocar sesiones por usuario -->

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>