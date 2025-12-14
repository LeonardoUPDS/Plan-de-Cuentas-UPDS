<?php
require_once __DIR__ . '/../../BD/conexion.php';
require_once __DIR__ . '/../../TEMPLATE/header.php';
require_once __DIR__ . '/../PERMISO/verificar_permiso.php';

if (!tienePermiso('Editar')) {
    die("No tienes permiso para editar cursos.");
}

$id = $_GET['id'] ?? 0;
if (!$id) {
    die('Curso no especificado.');
}

// traer curso
$stmt = $conexion->prepare('SELECT idCurso, Nombre, Descripcion FROM curso WHERE idCurso = :id');
$stmt->execute([':id' => $id]);
$curso = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$curso) die('Curso no encontrado.');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    if ($nombre === '') {
        $error = 'El nombre es obligatorio.';
    } else {
        $up = $conexion->prepare('UPDATE curso SET Nombre = :n, Descripcion = :d WHERE idCurso = :id');
        $up->execute([':n' => $nombre, ':d' => $descripcion, ':id' => $id]);
        header('Location: index.php?mensaje=' . urlencode('Curso actualizado'));
        exit;
    }
}
?>

<div class="container my-4">
  <div class="card">
    <div class="card-header">Editar Curso</div>
    <div class="card-body">
      <?php if (!empty($error)): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
      <form method="post">
        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input class="form-control" name="nombre" value="<?php echo htmlspecialchars($curso['Nombre']); ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Descripción</label>
          <textarea class="form-control" name="descripcion"><?php echo htmlspecialchars($curso['Descripcion']); ?></textarea>
        </div>
        <div class="d-grid">
          <button class="btn btn-primary">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>
