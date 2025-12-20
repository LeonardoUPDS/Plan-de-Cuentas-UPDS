<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../BD/conexion.php';

// Solo validar si el usuario está logueado
if (!isset($_SESSION['idUsuario'], $_SESSION['tokenSesion'])) {
    return; // no hay sesión activa para verificar
}

$sql = "
    SELECT idSesion
    FROM SesionUsuario
    WHERE idUsuario = :idUsuario
      AND TokenSesion = :token
      AND Activa = 1
";
$stmt = $conexion->prepare($sql);
$stmt->execute([
    ':idUsuario' => $_SESSION['idUsuario'],
    ':token' => $_SESSION['tokenSesion']
]);

if (!$stmt->fetch()) {
    // Sesión invalidada en BD — cerrar sesión local y redirigir al login
    session_unset();
    session_destroy();
    echo "<script>
        alert('Has excedido el límite de sesiones o tu sesión ha sido cerrada. Serás redirigido al inicio de sesión.');
        window.location.href='" . BASE_URL . "/AUTH/login.php';
    </script>";
    exit;
}
