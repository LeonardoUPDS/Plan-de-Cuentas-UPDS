<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require "../BD/conexion.php";

/* =====================================================
   0. PROTEGER ACCESO DIRECTO
===================================================== */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "/AUTH/login.php");
    exit;
}

/* =====================================================
   1. OBTENER Y VALIDAR INPUTS
===================================================== */
$correo      = trim($_POST['correo'] ?? '');
$contrasena  = $_POST['contrasena'] ?? '';

if ($correo === '' || $contrasena === '') {
    header("Location: login.php?error=" . urlencode("Credenciales inválidas"));
    exit;
} 

/* =====================================================
   2. BUSCAR USUARIO
===================================================== */
$stmt = $conexion->prepare("
    SELECT idusuario, contrasena, idrol, emailverificado
    FROM usuario
    WHERE correo = :correo
      AND estado = 1
");
$stmt->bindParam(":correo", $correo);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header("Location: login.php?error=" . urlencode("Usuario no encontrado"));
    exit;
} 

/* =====================================================
   3. VALIDACIONES DE SEGURIDAD
===================================================== */
if (!password_verify($contrasena, $usuario['contrasena'])) {
    header("Location: login.php?error=" . urlencode("Contraseña incorrecta"));
    exit;
} 

if (!$usuario['emailverificado']) {
    header("Location: login.php?error=" . urlencode("Debes verificar tu correo"));
    exit;
}

/* =====================================================
   4. CONTROL DE SESIONES POR PLAN
===================================================== */
require "../SECCION/SESION/control_sesiones.php";
require "../SECCION/SESION/registrar_sesion.php";

/* =====================================================
   5. INICIAR SESIÓN PHP
===================================================== */
$_SESSION['idUsuario'] = $usuario['idusuario'];
$_SESSION['idRol']     = $usuario['idrol'];

/* controlar y registrar */
controlarSesiones($conexion, $usuario['idusuario']);
registrarSesion($conexion, $usuario['idusuario']);

/* =====================================================
   6. REDIRECCIÓN FINAL
===================================================== */
header("Location: ../index.php");
exit;
