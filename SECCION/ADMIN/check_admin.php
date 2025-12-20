<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../SECCION/ROL/verificar_rol.php';

// Funcción para forzar acceso de admin (idRol == 3)
function requireAdmin() {
    if (empty($_SESSION['idUsuario'])) {
        header('Location: ' . BASE_URL . '/AUTH/login.php');
        exit;
    }
    if (!tieneRol(3)) {
        // opcional: mostrar 403
        http_response_code(403);
        echo '<!doctype html><html><head><meta charset="utf-8"><title>403 Forbidden</title></head><body><h1>403 - Acceso denegado</h1><p>Necesitas permisos de administrador para acceder a esta sección.</p><p><a href="' . BASE_URL . '/index.php">Volver</a></p></body></html>';
        exit;
    }
}
