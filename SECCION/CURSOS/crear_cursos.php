<?php
require_once __DIR__ . '/../../BD/conexion.php';
require_once __DIR__ . '/../../TEMPLATE/header.php';
require_once __DIR__ . '/../PERMISO/verificar_permiso.php';

if (!tienePermiso('Crear')) {
        die("No tienes permiso para crear cursos.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        if ($nombre === '') {
                $error = 'El nombre es obligatorio.';
        } else {
                $ins = $conexion->prepare('INSERT INTO curso (Nombre, Descripcion, Estado) VALUES (:n, :d, 1)');
                $ins->execute([':n' => $nombre, ':d' => $descripcion]);
                header('Location: index.php?mensaje=' . urlencode('Curso creado'));
                exit;
        }
}
?>

<div class="container my-4">
    <div class="card">
        <div class="card-header">Crear Curso</div>
        <div class="card-body">
            <?php if (!empty($error)): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input class="form-control" name="nombre" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-control" name="descripcion"></textarea>
                </div>
                <div class="d-grid">
                    <button class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>
