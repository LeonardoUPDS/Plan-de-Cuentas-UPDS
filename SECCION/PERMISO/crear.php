<?php
require_once __DIR__ . '/../../BD/conexion.php';
require_once __DIR__ . '/../../TEMPLATE/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descripcion = trim($_POST['descripcion'] ?? '');
    if ($descripcion === '') {
        $error = 'La descripción es obligatoria.';
    } else {
        $ins = $conexion->prepare('INSERT INTO permiso (descripcion, Estado) VALUES (:desc, 1)');
        $ins->execute([':desc' => $descripcion]);
        header('Location: index.php?mensaje=' . urlencode('Permiso creado'));
        exit;
    }
}
?>
<div class="container my-4">
  <div class="card">
    <div class="card-header">Crear Permiso</div>
    <div class="card-body">
      <?php if (!empty($error)): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
      <form method="post">
        <div class="mb-3">
          <label class="form-label">Descripción</label>
          <input class="form-control" name="descripcion" required>
        </div>
        <div class="d-grid">
          <button class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>
