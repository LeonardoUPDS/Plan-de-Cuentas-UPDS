<?php
session_start();
require "../../BD/conexion.php";

$idUsuario = $_SESSION['idUsuario'] ?? 0;
$idCurso = $_POST['idCurso'] ?? 0;

if (!$idUsuario || !$idCurso) {
    die("Datos incompletos.");
}

// Verificar si ya tiene acceso
$stmt = $conexion->prepare("
    SELECT * FROM accesocurso
    WHERE idUsuario = :idUsuario AND idCurso = :idCurso
");
$stmt->bindParam(':idUsuario', $idUsuario);
$stmt->bindParam(':idCurso', $idCurso);
$stmt->execute();
$existe = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existe) {
    die("Ya estás inscrito en este curso.");
}

// Insertar acceso
$stmt = $conexion->prepare("
    INSERT INTO accesocurso (idUsuario, idCurso) VALUES (:idUsuario, :idCurso)
");
$stmt->bindParam(':idUsuario', $idUsuario);
$stmt->bindParam(':idCurso', $idCurso);
$stmt->execute();

header("Location: ../CURSOS/index.php");
exit;
