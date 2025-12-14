<?php
session_start();
require "../../BD/conexion.php";

if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../../AUTH/login.php");
    exit;
}

$stmt = $conexion->prepare("
    SELECT idcurso, nombre, descripcion
    FROM curso
    WHERE estado = 1
");
$stmt->execute();
$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php
// Redirigir a la versión mejorada
header('Location: index.php');
exit;
?>
