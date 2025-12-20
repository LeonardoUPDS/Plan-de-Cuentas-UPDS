<?php
require_once __DIR__ . '/check_admin.php';
requireAdmin();
require_once __DIR__ . '/../../TEMPLATE/header.php';

// Listado de cursos
$stmt = $conexion->query('SELECT idCurso, Nombre, Descripcion, Estado FROM Curso ORDER BY idCurso DESC');
$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container my-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div><span>Cursos</span></div>
      <div>
        <a class="btn btn-secondary btn-sm me-2" href="<?= BASE_URL?>/SECCION/ADMIN/index.php">Volver</a>
        <a class="btn btn-primary btn-sm" href="crear_curso.php">Crear Curso</a>
      </div>
    </div>
    <div class="card-body">
      <?php if (!empty($_GET['success'])): ?><div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div><?php endif; ?>
      <?php if (!empty($_GET['error'])): ?><div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div><?php endif; ?>
      <table class="table table-hover">
        <thead><tr><th>Nombre</th><th>Descripción</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
          <?php foreach ($cursos as $c): ?>
            <tr>
              <td><?= htmlspecialchars($c['Nombre']) ?></td>
              <td><?= htmlspecialchars($c['Descripcion']) ?></td>
              <td><?= $c['Estado'] ? 'Activo' : 'Inactivo' ?></td>
              <td>
                <a class="btn btn-sm btn-info" href="editar_curso.php?id=<?= urlencode($c['idCurso']) ?>">Editar</a>
                <form method="POST" action="<?= BASE_URL?>/SECCION/ADMIN/toggle_estado.php" style="display:inline">
                  <input type="hidden" name="table" value="Curso">
                  <input type="hidden" name="id" value="<?= (int)$c['idCurso'] ?>">
                  <input type="hidden" name="back" value="<?= BASE_URL?>/SECCION/ADMIN/cursos.php">
                  <button type="submit" class="btn btn-sm <?= $c['Estado'] ? 'btn-danger' : 'btn-success' ?>" onclick="return confirm('¿Cambiar estado del curso?')"><?= $c['Estado'] ? 'Desactivar' : 'Activar' ?></button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <!-- Comentario: aquí puede agregar contenido adicional para gestionar el contenido del curso, módulos, lecciones, etc. -->

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>