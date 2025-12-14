<?php
require_once __DIR__ . '/../../BD/conexion.php';
require_once __DIR__ . '/../../TEMPLATE/header.php';
// Evitar aviso si la sesión ya fue iniciada en header.php
if (session_status() === PHP_SESSION_NONE) session_start();

$idUsuario = $_SESSION['idUsuario'] ?? 0;
// Traer cursos activos
$stmt = $conexion->prepare("SELECT idCurso, Nombre, Descripcion FROM curso WHERE Estado = TRUE");
$stmt->execute();
$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container my-4">
  <h2>Cursos disponibles</h2>
  <div class="row">
    <?php if (count($cursos) > 0): ?>
      <?php foreach ($cursos as $curso): ?>
        <div class="col-md-4">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title"><?php echo htmlspecialchars($curso['Nombre']); ?></h5>
              <p class="card-text"><?php echo htmlspecialchars($curso['Descripcion']); ?></p>
              <?php
                // comprobar inscripción
                $stmt2 = $conexion->prepare("SELECT * FROM accesocurso WHERE idUsuario = :idUsuario AND idCurso = :idCurso AND Estado = TRUE");
                $stmt2->execute([':idUsuario' => $idUsuario, ':idCurso' => $curso['idCurso']]);
                $acceso = $stmt2->fetch(PDO::FETCH_ASSOC);
              ?>
              <?php if ($acceso): ?>
                <a href="../ACCESOCURSOS/curso_contenido.php?id=<?php echo urlencode($curso['idCurso']); ?>" class="btn btn-primary">Ver contenido</a>
              <?php else: ?>
                <form method="POST" action="../ACCESOCURSOS/inscribirse.php" class="d-inline">
                  <input type="hidden" name="idCurso" value="<?php echo htmlspecialchars($curso['idCurso']); ?>">
                  <button class="btn btn-success">Inscribirse</button>
                </form>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12">No hay cursos disponibles.</div>
    <?php endif; ?>
  </div>
</div>

<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>
