<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../BD/conexion.php";
require "../PHPMAILER/PHPMailer.php";
require "../PHPMAILER/SMTP.php";
require "../PHPMAILER/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;

$correo = trim($_POST['correo']);
$contrasena = $_POST['contrasena'];

// 1️⃣ Revisar si el correo ya existe
$stmt = $conexion->prepare("SELECT * FROM $tablaUsuario WHERE Correo = :correo");
$stmt->bindParam(":correo", $correo);
$stmt->execute();
$usuarioExistente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuarioExistente) {
    if ($usuarioExistente['EmailVerificado']) {
        die("Este correo ya está registrado y verificado. Por favor inicia sesión.");
    } else {
        // Usuario existente pero NO verificado -> generamos nuevo token
        $idUsuario = $usuarioExistente['idUsuario'];
    }
} else {
    // 2️⃣ Insertar nuevo usuario
    $hash = password_hash($contrasena, PASSWORD_DEFAULT);
    $stmt = $conexion->prepare("
        INSERT INTO $tablaUsuario (Correo, Contrasena, idRol, EmailVerificado, Estado)
        VALUES (:correo, :contrasena, 1, FALSE, 1)
    ");
    $stmt->bindParam(":correo", $correo);
    $stmt->bindParam(":contrasena", $hash);
    $stmt->execute();
    $idUsuario = $conexion->lastInsertId();
}

// 3️⃣ Generar token de verificación
$token = bin2hex(random_bytes(32));
$expira = date('Y-m-d H:i:s', strtotime('+24 hours'));

// Insertamos el token
$stmt = $conexion->prepare("
    INSERT INTO $tablaToken (idUsuario, Token, FechaExpiracion, Usado)
    VALUES (:idUsuario, :token, :expira, 0)
");
$stmt->bindParam(":idUsuario", $idUsuario, PDO::PARAM_INT);
$stmt->bindParam(":token", $token);
$stmt->bindParam(":expira", $expira);
$stmt->execute();

// 4️⃣ Enviar correo
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'Leonardo75604458@gmail.com';
    $mail->Password = 'tlni kpoa qukh ikml';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('Leonardo75604458@gmail.com', 'Plataforma Educativa');
    $mail->addAddress($correo);
    $mail->isHTML(true);
    $mail->Subject = 'Verifica tu cuenta';
    $mail->Body = "
        <p>Haz clic en el enlace para verificar tu cuenta:</p>
        <a href='" . BASE_URL . "/AUTH/verify.php?token=$token'>Verificar cuenta</a>
    ";

    $mail->send();
    echo "Registro exitoso. Revisa tu correo para verificar tu cuenta.";

} catch (Exception $e) {
    echo "No se pudo enviar el correo. Error: {$mail->ErrorInfo}";
}
?>