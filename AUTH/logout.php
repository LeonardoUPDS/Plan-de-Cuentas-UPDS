<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require "../BD/conexion.php";

if (!empty($_SESSION['tokenSesion'])) {
    // Recuperar el hash guardado en la BD para este usuario
    $sql = "SELECT TokenSesion FROM sesionusuario WHERE idUsuario = :id AND Activa = 1";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id' => $_SESSION['idUsuario']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($_SESSION['tokenSesion'], $row['TokenSesion'])) {
        // Si el token de la sesión coincide con el hash en BD, desactivar la sesión
        $sql = "UPDATE sesionusuario SET Activa = 0 WHERE idUsuario = :id AND TokenSesion = :tokenHash";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ':id' => $_SESSION['idUsuario'],
            ':tokenHash' => $row['TokenSesion']
        ]);
    }
}

// Destruir la sesión PHP
session_unset();
session_destroy();

// Redirigir al login
header("Location: " . BASE_URL . "/AUTH/login.php");
exit;
?>
