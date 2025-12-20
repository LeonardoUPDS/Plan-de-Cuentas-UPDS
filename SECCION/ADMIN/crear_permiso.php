<?php
require_once __DIR__ . '/check_admin.php';
requireAdmin();
require_once __DIR__ . '/../../BD/conexion.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descripcion = trim($_POST['descripcion'] ?? '');
    if ($descripcion === '') { $error = 'Descripción requerida'; }
    else { $ins = $conexion->prepare('INSERT INTO Permiso (descripcion, Estado) VALUES (:desc, 1)'); $ins->execute([':desc'=>$descripcion]); header('Location: permisos.php?success=' . urlencode('Permiso creado')); exit; }
}
require_once __DIR__ . '/../../TEMPLATE/header.php';
?>
<div class="container my-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div><span>Crear Permiso</span></div>
      <div><a class="btn btn-secondary btn-sm" href="<?= BASE_URL?>/SECCION/ADMIN/permisos.php">Volver</a></div>
    </div>
    <div class="card-body">
      <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <form method="POST">
        <div class="mb-3"><label class="form-label">Descripción</label><input class="form-control" name="descripcion" required></div>
        <button class="btn btn-primary">Crear</button>
      </form>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>