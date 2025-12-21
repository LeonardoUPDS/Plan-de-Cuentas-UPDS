<?php
require_once __DIR__ . '/check_admin.php';
requireAdmin();
require_once __DIR__ . '/../../BD/conexion.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: ' . BASE_URL . '/SECCION/ADMIN/sesiones.php'); exit;
}

$stmt = $conexion->prepare('UPDATE sesionusuario SET Activa = 0 WHERE idSesion = :id');
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

header('Location: ' . BASE_URL . '/SECCION/ADMIN/sesiones.php?success=' . urlencode('Sesión revocada'));
exit;