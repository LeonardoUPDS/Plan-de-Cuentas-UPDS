<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../BD/conexion.php';

/**
 * Verifica si el usuario tiene el rol solicitado
 * @param int $rolId ID del rol
 * @return bool
 */
function tieneRol($rolId) {
    global $conexion;
    $idUsuario = $_SESSION['idUsuario'] ?? 0;
    if (!$idUsuario) return false;

    $stmt = $conexion->prepare("SELECT idRol FROM usuario WHERE idUsuario = :idUsuario AND Estado = TRUE");
    $stmt->bindParam(':idUsuario', $idUsuario);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    return $usuario && $usuario['idRol'] == $rolId;
}
