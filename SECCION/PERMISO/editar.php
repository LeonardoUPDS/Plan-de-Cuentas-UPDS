<?php
require_once __DIR__ . '/../../BD/conexion.php';
require_once __DIR__ . '/../../TEMPLATE/header.php';

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: index.php'); exit; }

$stmt = $conexion->prepare('SELECT permiso_id, descripcion FROM permiso WHERE permiso_id = :id');
$stmt->execute([':id' => $id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$item) { header('Location: index.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descripcion = trim($_POST['descripcion'] ?? '');
    if ($descripcion === '') {
        $error = 'La descripción es obligatoria.';
    } else {
        $upd = $conexion->prepare('UPDATE permiso SET descripcion = :desc WHERE permiso_id = :id');
        $upd->execute([':desc' => $descripcion, ':id' => $id]);
        header('Location: index.php?mensaje=' . urlencode('Permiso actualizado'));
        exit;
    }
}
?>
<div class="container my-4">
  <div class="card">
    <div class="card-header">Editar Permiso</div>
    <div class="card-body">
      <?php if (!empty($error)): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
      <form method="post">
        <div class="mb-3">
          <label class="form-label">Descripción</label>
          <input class="form-control" name="descripcion" value="<?= htmlspecialchars($item['descripcion']) ?>" required>
        </div>
        <div class="d-grid">
          <button class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>
