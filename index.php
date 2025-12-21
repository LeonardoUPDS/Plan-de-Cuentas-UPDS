<?php
session_start();
require "BD/conexion.php";

//echo "<p>✅ Script iniciado correctamente</p>";

if (!isset($_SESSION['idUsuario'])) {
    //echo "<p>⚠️ No hay sesión activa, redirigiendo a login...</p>";
    header("Location: AUTH/login.php");
    exit;
} else {
    //echo "<p>✅ Sesión detectada: idUsuario = " . $_SESSION['idUsuario'] . "</p>";
}
require_once __DIR__ . '/TEMPLATE/header.php';
//echo "<p>✅ Header cargado</p>";
?>
<?php if(tieneRol(3)): ?>
    <?php require_once __DIR__ . '/SECCION/ADMIN/index.php'; ?>
<?php else: ?>
    <div class="container my-4">
        <div class="card">
            <div class="card-body">
                <h2>Bienvenido a la Plataforma Educativa</h2>
                <p>
                    <a href="SECCION/CURSOS/index.php" class="btn btn-primary">📚 Ver Cursos</a>
                    <a href="AUTH/logout.php" class="btn btn-secondary">🚪 Cerrar sesión</a>
                </p>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php 
//echo "<p>✅ Footer cargado</p>";
require_once __DIR__ . '/TEMPLATE/footer.php'; 
?>

