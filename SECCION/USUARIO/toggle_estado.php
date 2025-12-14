<?php
require_once __DIR__ . '/../../BD/conexion.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

// obtener estado actual
$stmt = $conexion->prepare('SELECT Estado FROM usuario WHERE idUsuario = :id');
$stmt->execute([':id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    header('Location: index.php');
    exit;
}

$new = ($user['Estado'] == 1) ? 0 : 1;
$upd = $conexion->prepare('UPDATE usuario SET Estado = :estado WHERE idUsuario = :id');
$upd->execute([':estado' => $new, ':id' => $id]);

$mensaje = $new == 1 ? 'Usuario activado' : 'Usuario desactivado';
header('Location: index.php?mensaje=' . urlencode($mensaje));
exit;
?>