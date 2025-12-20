<?php
require_once __DIR__ . '/../../BD/conexion.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (empty($_SESSION['idUsuario'])) {
    header('Location: ' . BASE_URL . '/AUTH/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/SECCION/USUARIO/perfil.php');
    exit;
}

$idPlan = (int) ($_POST['idPlan'] ?? 0);
$idUsuario = (int) $_SESSION['idUsuario'];

// validar plan
$stmt = $conexion->prepare("SELECT idPlan FROM Plan WHERE idPlan = :idPlan AND Estado = 1");
$stmt->bindParam(':idPlan', $idPlan, PDO::PARAM_INT);
$stmt->execute();
$plan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$plan) {
    header('Location: ' . BASE_URL . '/SECCION/USUARIO/perfil.php?error=' . urlencode('Plan no válido'));
    exit;
}

try {
    $conexion->beginTransaction();

    // desactivar suscripciones previas
    $stmt = $conexion->prepare("UPDATE Suscripcion SET Estado = 0 WHERE idUsuario = :idUsuario AND Estado = 1");
    $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();

    // insertar nueva suscripcion (30 días por defecto)
    $stmt = $conexion->prepare("INSERT INTO Suscripcion (idUsuario, idPlan, FechaInicio, FechaFin, Estado) VALUES (:idUsuario, :idPlan, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 1)");
    $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
    $stmt->bindParam(':idPlan', $idPlan, PDO::PARAM_INT);
    $stmt->execute();

    $conexion->commit();

    header('Location: ' . BASE_URL . '/SECCION/USUARIO/perfil.php?success=' . urlencode('Plan actualizado'));
    exit;
} catch (Exception $e) {
    $conexion->rollBack();
    header('Location: ' . BASE_URL . '/SECCION/USUARIO/perfil.php?error=' . urlencode('No se pudo cambiar de plan'));
    exit;
}
