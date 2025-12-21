<?php
require_once __DIR__ . '/check_admin.php';
requireAdmin();
require_once __DIR__ . '/../../BD/conexion.php';
$id = (int)($_GET['id'] ?? 0); if ($id <= 0) { header('Location: permisos.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') { $descripcion = trim($_POST['descripcion'] ?? ''); $estado = isset($_POST['Estado']) ? 1 : 0; if ($descripcion === '') { $error = 'Descripción requerida'; } else { $stmt = $conexion->prepare('UPDATE Permiso SET descripcion = :desc, Estado = :estado WHERE permiso_id = :id'); $stmt->execute([':desc'=>$descripcion,':estado'=>$estado,':id'=>$id]); header('Location: permisos.php?success=' . urlencode('Permiso actualizado')); exit; } }
$stmt = $conexion->prepare('SELECT permiso_id, descripcion, Estado FROM permiso WHERE permiso_id = :id'); $stmt->execute([':id'=>$id]); $r = $stmt->fetch(PDO::FETCH_ASSOC); if (!$r) { header('Location: permisos.php?error=' . urlencode('Permiso no encontrado')); exit; }
require_once __DIR__ . '/../../TEMPLATE/header.php';
?>
<div class="container my-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div><span>Editar Permiso</span></div>
      <div><a class="btn btn-secondary btn-sm" href="<?= BASE_URL?>/SECCION/ADMIN/permisos.php">Volver</a></div>
    </div>
    <div class="card-body">
      <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <form method="POST">
        <div class="mb-3"><label class="form-label">Descripción</label><input class="form-control" name="descripcion" value="<?= htmlspecialchars($r['descripcion']) ?>" required></div>
        <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="Estado" id="estado" <?= $r['Estado'] ? 'checked' : '' ?>><label class="form-check-label" for="estado">Activo</label></div>
        <button class="btn btn-primary">Guardar</button>
      </form>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>