<?php
require_once __DIR__ . '/check_admin.php';
requireAdmin();
require_once __DIR__ . '/../../BD/conexion.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: planes.php'); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['Nombre'] ?? '');
    $max = (int)($_POST['MaxSesiones'] ?? 1);
    $precio = (float)($_POST['Precio'] ?? 0);
    $estado = isset($_POST['Estado']) ? 1 : 0;

    if ($nombre === '') {
        header('Location: editar_plan.php?id=' . $id . '&error=' . urlencode('Nombre requerido'));
        exit;
    }

    $stmt = $conexion->prepare('UPDATE plan SET Nombre = :nombre, MaxSesiones = :max, Precio = :precio, Estado = :estado WHERE idPlan = :id');
    $stmt->execute([':nombre'=>$nombre, ':max'=>$max, ':precio'=>$precio, ':estado'=>$estado, ':id'=>$id]);

    header('Location: planes.php?success=' . urlencode('Plan actualizado'));
    exit;
}

$stmt = $conexion->prepare('SELECT idPlan, Nombre, MaxSesiones, Precio, Estado FROM plan WHERE idPlan = :id');
$stmt->execute([':id'=>$id]);
$plan = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$plan) { header('Location: planes.php?error=' . urlencode('Plan no encontrado')); exit; }

require_once __DIR__ . '/../../TEMPLATE/header.php';
?>
<div class="container my-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div><span>Editar Plan</span></div>
      <div>
        <a class="btn btn-secondary btn-sm" href="<?= BASE_URL?>/SECCION/ADMIN/planes.php">Volver</a>
      </div>
    </div>
    <div class="card-body">
      <?php if (!empty($_GET['error'])): ?><div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div><?php endif; ?>
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input class="form-control" name="Nombre" value="<?= htmlspecialchars($plan['Nombre']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Max sesiones</label>
          <input class="form-control" name="MaxSesiones" type="number" value="<?= htmlspecialchars($plan['MaxSesiones']) ?>" min="1" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Precio</label>
          <input class="form-control" name="Precio" type="number" step="0.01" value="<?= htmlspecialchars($plan['Precio']) ?>">
        </div>
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="Estado" id="estado" <?= $plan['Estado'] ? 'checked' : '' ?>>
          <label class="form-check-label" for="estado">Activo</label>
        </div>
        <button class="btn btn-primary">Guardar</button>
      </form>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>