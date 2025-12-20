<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require "../BD/conexion.php";

if (!empty($_SESSION['tokenSesion'])) {
    $sql = "
        UPDATE sesionusuario
        SET Activa = 0
        WHERE tokensesion = :token
    ";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':token' => $_SESSION['tokenSesion']]);
}

session_unset();
session_destroy();
header("Location: " . BASE_URL . "/AUTH/login.php");
exit;

?>