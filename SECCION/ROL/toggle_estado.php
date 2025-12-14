<?php
require_once __DIR__ . '/../../BD/conexion.php';

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: index.php'); exit; }

$stmt = $conexion->prepare('SELECT Estado FROM rol WHERE idRol = :id');
$stmt->execute([':id' => $id]);
$r = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$r) { header('Location: index.php'); exit; }

$new = ($r['Estado'] == 1) ? 0 : 1;
$upd = $conexion->prepare('UPDATE rol SET Estado = :e WHERE idRol = :id');
$upd->execute([':e' => $new, ':id' => $id]);

header('Location: index.php?mensaje=' . urlencode($new ? 'Rol activado' : 'Rol desactivado'));
exit;
?>
