<?php
require_once __DIR__ . '/check_admin.php';
requireAdmin();
require_once __DIR__ . '/../../BD/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['Nombre'] ?? '');
    $descripcion = trim($_POST['Descripcion'] ?? '');
    $estado = isset($_POST['Estado']) ? 1 : 0;

    if ($nombre === '') {
        header('Location: crear_curso.php?error=' . urlencode('Nombre requerido'));
        exit;
    }

    $stmt = $conexion->prepare('INSERT INTO curso (Nombre, Descripcion, Estado) VALUES (:nombre, :desc, :estado)');
    $stmt->execute([':nombre'=>$nombre, ':desc'=>$descripcion, ':estado'=>$estado]);

    header('Location: cursos.php?success=' . urlencode('Curso creado'));
    exit;
}

require_once __DIR__ . '/../../TEMPLATE/header.php';
?>
<div class="container my-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div><span>Crear Curso</span></div>
      <div>
        <a class="btn btn-secondary btn-sm" href="<?= BASE_URL?>/SECCION/ADMIN/cursos.php">Volver</a>
      </div>
    </div>
    <div class="card-body">
      <?php if (!empty($_GET['error'])): ?><div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div><?php endif; ?>
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input class="form-control" name="Nombre" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Descripción</label>
          <textarea class="form-control" name="Descripcion"></textarea>
        </div>
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="Estado" id="estado" checked>
          <label class="form-check-label" for="estado">Activo</label>
        </div>
        <button class="btn btn-primary">Crear</button>
      </form>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>