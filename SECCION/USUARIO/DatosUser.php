<?php //regusuario.php ?>

<?php require_once("../../conexion.php");

$Correo = $_POST['email'];
$Contrasena = password_hash($_POST['pass'], PASSWORD_DEFAULT);
$Estado = True;
$idRol = 1;
$EmailVerificado = True;

$sql = "INSERT INTO usuario (Correo, Contrasena, Estado, idRol)  
VALUES ('$Correo', '$Contrasena', $Estado, $idRol);";

$conexion->query($sql);

header('Location: index.php');
?>