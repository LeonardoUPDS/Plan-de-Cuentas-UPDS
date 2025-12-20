<?php
session_start();
require "BD/conexion.php";

if (!isset($_SESSION['idUsuario'])) {
        header("Location: " . BASE_URL . "/AUTH/login.php");
        exit;
}
require_once __DIR__ . '/TEMPLATE/header.php';
?>

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

<?php require_once __DIR__ . '/TEMPLATE/footer.php'; ?>
