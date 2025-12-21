<?php
require_once __DIR__ . '/check_admin.php';
requireAdmin();
require_once __DIR__ . '/../../TEMPLATE/header.php';

$stmt = $conexion->query('SELECT s.idSuscripcion, u.Correo, p.Nombre AS Plan, s.FechaInicio, s.FechaFin, s.Estado FROM suscripcion s JOIN usuario u ON s.idUsuario = u.idUsuario JOIN plan p ON s.idPlan = p.idPlan ORDER BY s.FechaInicio DESC');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container my-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div><span>Suscripciones</span></div>
      <div>
        <a class="btn btn-secondary btn-sm" href="<?= BASE_URL?>/SECCION/ADMIN/index.php">Volver</a>
      </div>
    </div>
    <div class="card-body">
      <table class="table table-sm">
        <thead><tr><th>Usuario</th><th>Plan</th><th>Inicio</th><th>Fin</th><th>Estado</th></tr></thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['Correo']) ?></td>
              <td><?= htmlspecialchars($r['Plan']) ?></td>
              <td><?= htmlspecialchars($r['FechaInicio']) ?></td>
              <td><?= htmlspecialchars($r['FechaFin']) ?></td>
              <td>
                <?= $r['Estado'] ? 'Activa' : 'Inactiva' ?>
                <form method="POST" action="<?= BASE_URL?>/SECCION/ADMIN/toggle_estado.php" style="display:inline">
                  <input type="hidden" name="table" value="Suscripcion">
                  <input type="hidden" name="id" value="<?= (int)$r['idSuscripcion'] ?>">
                  <input type="hidden" name="back" value="<?= BASE_URL?>/SECCION/ADMIN/suscripciones.php">
                  <button type="submit" class="btn btn-sm <?= $r['Estado'] ? 'btn-danger' : 'btn-success' ?>" onclick="return confirm('¿Cambiar estado de la suscripción?')"><?= $r['Estado'] ? 'Desactivar' : 'Activar' ?></button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <!-- Comentario: añadir filtros por usuario/plan, export CSV, gestión masiva -->

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>