<?php
require_once __DIR__ . '/check_admin.php';
requireAdmin();
require_once __DIR__ . '/../../TEMPLATE/header.php';

$stmt = $conexion->query('SELECT s.idSuscripcion, u.Correo, p.Nombre AS Plan, s.FechaInicio, s.FechaFin, s.Estado FROM Suscripcion s JOIN Usuario u ON s.idUsuario = u.idUsuario JOIN Plan p ON s.idPlan = p.idPlan ORDER BY s.FechaInicio DESC');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container my-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span>Suscripciones</span>
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
              <td><?= $r['Estado'] ? 'Activa' : 'Inactiva' ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <!-- Comentario: añadir filtros por usuario/plan, export CSV, gestión masiva -->

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>