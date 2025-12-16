<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require "../BD/conexion.php";

$correo = trim($_POST['correo'] ?? '');
$contrasena = $_POST['contrasena'] ?? '';

if (!$correo || !$contrasena) {
    die("Credenciales inválidas");
}

$stmt = $conexion->prepare("
    SELECT idUsuario, Contrasena, idRol, EmailVerificado
    FROM usuario
    WHERE Correo = :correo AND Estado = 1
");
$stmt->bindParam(":correo", $correo);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!password_verify($contrasena, $usuario['Contrasena'])) {
    die("Contraseña incorrecta");
}

if (!$usuario['EmailVerificado']) {
    die("Debes verificar tu correo");
}

$_SESSION['idUsuario'] = $usuario['idUsuario'];
$_SESSION['idRol'] = $usuario['idRol'];

header("Location: ../index.php");
exit;
?>