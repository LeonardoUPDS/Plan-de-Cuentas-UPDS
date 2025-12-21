<?php
require_once __DIR__ . '/../../BD/conexion.php';
require_once __DIR__ . '/../../TEMPLATE/header.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$idUsuario = $_SESSION['idUsuario'] ?? 0;
if (!$idUsuario) {
    header('Location: ' . BASE_URL . '/AUTH/login.php'); exit;
}

// Traer cursos donde el usuario está inscrito y el acceso está activo
$stmt = $conexion->prepare("SELECT c.idCurso, c.Nombre, c.Descripcion, a.FechaAcceso FROM accesocurso a JOIN curso c ON a.idCurso = c.idCurso WHERE a.idUsuario = :idUsuario AND a.Estado = 1 AND c.Estado = 1");
$stmt->execute([':idUsuario' => $idUsuario]);
$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container my-4">
  <h2>Mis cursos</h2>
  <p>Estos son los cursos en los que estás inscrito. Si quieres inscribirte en más cursos, pulsa en <strong>Cursos</strong> para ver todos los cursos disponibles.</p>
  <a class="btn btn-outline-primary mb-3" href="<?= BASE_URL ?>/SECCION/CURSOS/index.php">Cursos</a>

  <div class="row">
    <?php if (count($cursos) > 0): ?>
      <?php foreach ($cursos as $curso): ?>
        <div class="col-md-4">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($curso['Nombre']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($curso['Descripcion']) ?></p>
              <p class="text-muted">Accedido: <?= htmlspecialchars($curso['FechaAcceso']) ?></p>
              <a href="../ACCESOCURSOS/curso_contenido.php?id=<?= urlencode($curso['idCurso']) ?>" class="btn btn-primary">Ver contenido</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12">No estás inscrito en ningún curso.</div>
    <?php endif; ?>
  </div>
</div>

<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>