<?php
require "../BD/conexion.php";

$token = $_POST['token'] ?? '';
$nueva = $_POST['nueva_contrasena'] ?? '';
$confirmar = $_POST['confirmar_contrasena'] ?? '';

if (!$token) {
    die("Token no proporcionado");
}

if ($nueva !== $confirmar) {
    die("Las contraseñas no coinciden");
}

$hash = password_hash($nueva, PASSWORD_DEFAULT);

// Validar token
$stmt = $conexion->prepare("
    SELECT idUsuario
    FROM tokenrecuperacion
    WHERE Token = :token
      AND Usado = 0
      AND FechaExpiracion > NOW()
");
$stmt->bindParam(":token", $token);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die("Token inválido o expirado");
}

// Cambiar contraseña
$conexion->prepare("
    UPDATE usuario SET Contrasena = :hash WHERE idUsuario = :id
")->execute([
    ':hash' => $hash,
    ':id' => $data['idUsuario']
]);

// Marcar token como usado
$conexion->prepare("
    UPDATE tokenrecuperacion SET Usado = 1 WHERE Token = :token
")->execute([':token' => $token]);

echo "Contraseña actualizada correctamente. Ya puedes iniciar sesión.";
