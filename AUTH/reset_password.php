<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require "../BD/conexion.php";

$token = $_GET['token'] ?? '';

if (!$token) {
    die("Token no proporcionado.");
}

// Verificar token
$stmt = $conexion->prepare("
    SELECT * FROM tokenrecuperacion
    WHERE Token = :token
      AND Usado = 0
      AND FechaExpiracion > NOW()
");
$stmt->bindParam(":token", $token);
$stmt->execute();
$tokenData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tokenData) {
    die("Token inválido o expirado.");
}
?>

<h2>Restablecer contraseña</h2>

<form method="POST" action="reset_password_process.php">
    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
    <input type="password" name="nueva_contrasena" required>
    <input type="password" name="confirmar_contrasena" required>
    <button type="submit">Cambiar contraseña</button>
</form>
