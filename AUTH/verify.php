<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../BD/conexion.php";

// ===============================
// TABLAS SEGÚN ENTORNO
// ===============================
if (PRODUCCION) {
    $tablaUsuario = "usuario";
    $tablaToken = "tokenrecuperacion";
} else {
    $tablaUsuario = "usuario";
    $tablaToken = "tokenrecuperacion";
}

// ===============================
// RECIBIR TOKEN
// ===============================
$token = $_GET['token'] ?? '';

if (!$token) {
    die("Token no proporcionado.");
}

// ===============================
// BUSCAR TOKEN VÁLIDO
// ===============================
$stmt = $conexion->prepare("
    SELECT * FROM $tablaToken
    WHERE Token = :token
      AND Usado = FALSE
      AND FechaExpiracion > NOW()
");

$stmt->bindParam(":token", $token);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($data) {
    // ===============================
    // MARCAR USUARIO COMO VERIFICADO
    // ===============================
    $stmt = $conexion->prepare("
        UPDATE $tablaUsuario
        SET EmailVerificado = TRUE
        WHERE idUsuario = :idUsuario
    ");
    $stmt->bindParam(":idUsuario", $data['idUsuario'], PDO::PARAM_INT);
    $stmt->execute();

    // ===============================
    // MARCAR TOKEN COMO USADO
    // ===============================
    $stmt = $conexion->prepare("
        UPDATE $tablaToken
        SET Usado = TRUE
        WHERE idToken = :idToken
    ");
    $stmt->bindParam(":idToken", $data['idToken'], PDO::PARAM_INT);
    $stmt->execute();

    echo "¡Cuenta verificada exitosamente! Ya puedes iniciar sesión.";
} else {
    echo "Token inválido o expirado.";
}
