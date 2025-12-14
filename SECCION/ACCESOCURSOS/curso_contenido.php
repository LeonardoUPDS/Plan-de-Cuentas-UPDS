<?php
session_start();
require "../../BD/conexion.php";

$idUsuario = $_SESSION['idUsuario'] ?? 0;
$idCurso = $_GET['id'] ?? 0;

if (!$idUsuario || !$idCurso) {
    die("Datos incompletos.");
}

// Verificar si tiene acceso
$stmt = $conexion->prepare("
    SELECT * FROM accesocurso
    WHERE idUsuario = :idUsuario AND idCurso = :idCurso AND Estado = TRUE
");
$stmt->bindParam(':idUsuario', $idUsuario);
$stmt->bindParam(':idCurso', $idCurso);
$stmt->execute();
$acceso = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$acceso) {
    die("No tienes acceso a este curso.");
}

require_once __DIR__ . '/../../TEMPLATE/header.php';

// Traer info del curso
$stmt = $conexion->prepare("SELECT * FROM curso WHERE idCurso = :idCurso");
$stmt->bindParam(':idCurso', $idCurso);
$stmt->execute();
$curso = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<div class="container my-4">
    <div class="card">
        <div class="card-body">
            <h2><?php echo htmlspecialchars($curso['Nombre']); ?></h2>
            <p><?php echo htmlspecialchars($curso['Descripcion']); ?></p>

            <h4>Participantes</h4>
            <?php
            $stmt = $conexion->prepare(" 
                    SELECT u.Correo, r.Descripcion AS Rol
                    FROM accesocurso a
                    JOIN usuario u ON a.idUsuario = u.idUsuario
                    JOIN rol r ON u.idRol = r.idRol
                    WHERE a.idCurso = :idCurso
            ");
            $stmt->bindParam(':idCurso', $idCurso);
            $stmt->execute();
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <ul>
                <?php foreach ($usuarios as $u): ?>
                    <li><?= htmlspecialchars($u['Correo']) ?> (<?= htmlspecialchars($u['Rol']) ?>)</li>
                <?php endforeach; ?>
            </ul>

            <!-- Aquí podrías agregar las lecciones o contenidos del curso -->
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>
