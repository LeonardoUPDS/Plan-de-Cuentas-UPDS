<?php
session_start();
require "../../BD/conexion.php";

if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../../AUTH/login.php");
    exit;
}

$idusuario = $_SESSION['idUsuario'];

$stmt = $conexion->prepare("
    SELECT c.idcurso, c.nombre, c.descripcion
    FROM curso c
    INNER JOIN accesocurso a ON c.idcurso = a.idcurso
    WHERE a.idusuario = :idusuario
      AND a.estado = 1
");
$stmt->execute([':idusuario' => $idusuario]);

$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Mis Cursos</h2>

<?php foreach ($cursos as $curso): ?>
    <div>
        <h3><?= htmlspecialchars($curso['nombre']) ?></h3>
        <a href="../CURSOS/ver_curso.php?id=<?= $curso['idcurso'] ?>">
            <?php
            session_start();
            require "../../BD/conexion.php";
            require_once __DIR__ . '/../../TEMPLATE/header.php';

            if (!isset($_SESSION['idUsuario'])) {
                    header("Location: ../../AUTH/login.php");
                    exit;
            }

            $idusuario = $_SESSION['idUsuario'];

            $stmt = $conexion->prepare(
                    "SELECT c.idCurso, c.Nombre, c.Descripcion
                     FROM curso c
                     INNER JOIN accesocurso a ON c.idCurso = a.idCurso
                     WHERE a.idUsuario = :idusuario
                         AND a.Estado = TRUE"
            );
            $stmt->execute([':idusuario' => $idusuario]);

            $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <div class="container mt-4">
                <h2>Mis Cursos</h2>
                <div class="row">
                    <?php if (count($cursos) === 0): ?>
                        <div class="col-12">No estás inscrito en ningún curso.</div>
                    <?php endif; ?>
                    <?php foreach ($cursos as $curso): ?>
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($curso['Nombre']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($curso['Descripcion']); ?></p>
                                    <a href="../ACCESOCURSOS/curso_contenido.php?id=<?php echo urlencode($curso['idCurso']); ?>" class="btn btn-primary">Ver curso</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>
