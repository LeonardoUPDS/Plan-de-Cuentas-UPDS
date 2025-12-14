<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../BD/conexion.php";
require "../PHPMAILER/PHPMailer.php";
require "../PHPMAILER/SMTP.php";
require "../PHPMAILER/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;

$correo = trim($_POST['correo'] ?? '');

if (!$correo) {
    die("Debes ingresar un correo.");
}

// Verificar si el usuario existe y está activo
$stmt = $conexion->prepare("SELECT * FROM usuario WHERE Correo = :correo AND Estado = TRUE");
$stmt->bindParam(":correo", $correo);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die("Usuario no encontrado.");
}

// Generar token de recuperación
$token = bin2hex(random_bytes(32));
$expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

$stmt = $conexion->prepare("INSERT INTO tokenrecuperacion (idUsuario, Token, FechaExpiracion, Usado) VALUES (:idUsuario, :token, :expira, 0)");
$stmt->bindParam(":idUsuario", $usuario['idUsuario']);
$stmt->bindParam(":token", $token);
$stmt->bindParam(":expira", $expira);
$stmt->execute();

// Enviar correo
$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'Leonardo75604458@gmail.com'; // Cambiar
$mail->Password = 'tlni kpoa qukh ikml';      // Cambiar
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

$mail->setFrom('Leonardo75604458@gmail.com', 'Plataforma Educativa');
$mail->addAddress($correo);
$mail->isHTML(true);
$mail->Subject = 'Recuperar contraseña';
$mail->Body = "
    Haz clic en el enlace para restablecer tu contraseña:<br>
    <a href='" . BASE_URL . "/AUTH/reset_password.php?token=$token'>Restablecer contraseña</a>
";

try {
    $mail->send();
    echo "Se envió un correo de recuperación. Revisa tu bandeja.";
} catch (Exception $e) {
    echo "No se pudo enviar el correo: {$mail->ErrorInfo}";
}
