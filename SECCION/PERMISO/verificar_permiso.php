<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../BD/conexion.php';

/**
 * Verifica si el usuario tiene el permiso solicitado
 * @param string $permisoDescripcion
 * @return bool
 */
function tienePermiso($permisoDescripcion) {
    global $conexion;
    $idUsuario = $_SESSION['idUsuario'] ?? 0;
    if (!$idUsuario) return false;

    $stmt = $conexion->prepare("
        SELECT d.permiso_id 
        FROM detalle d
        JOIN permiso p ON d.permiso_id = p.permiso_id
        JOIN usuario u ON u.idRol = d.idRol
        WHERE u.idUsuario = :idUsuario
          AND p.descripcion = :permiso
          AND d.Estado = TRUE
    ");
    $stmt->bindParam(':idUsuario', $idUsuario);
    $stmt->bindParam(':permiso', $permisoDescripcion);
    $stmt->execute();

    return $stmt->rowCount() > 0;
}
