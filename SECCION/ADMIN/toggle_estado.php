<?php
// Toggle global de estado para recursos del panel de administración
// Uso: POST a este archivo con los campos: table (tabla), id (valor de id), back (url de retorno opcional)
// La implementación usa una lista blanca para evitar inyecciones SQL. Añade nuevas tablas al array $allowed según necesidad.
require_once __DIR__ . '/check_admin.php';
requireAdmin();
require_once __DIR__ . '/../../BD/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/SECCION/ADMIN/index.php');
    exit;
}

$table = $_POST['table'] ?? '';
$id = (int)($_POST['id'] ?? 0);
$back = $_POST['back'] ?? (BASE_URL . '/SECCION/ADMIN/index.php');

// Lista blanca de tablas y columnas admitidas
$allowed = [
    'Usuario' => ['id' => 'idUsuario', 'state' => 'Estado'],
    'Rol' => ['id' => 'idRol', 'state' => 'Estado'],
    'Permiso' => ['id' => 'permiso_id', 'state' => 'Estado'],
    'Plan' => ['id' => 'idPlan', 'state' => 'Estado'],
    'Suscripcion' => ['id' => 'idSuscripcion', 'state' => 'Estado'],
    'Curso' => ['id' => 'idCurso', 'state' => 'Estado'],
    'Detalle' => ['id' => 'detalle_id', 'state' => 'Estado'],
    'AccesoCurso' => ['id' => 'idAcceso', 'state' => 'Estado'],
    'SesionUsuario' => ['id' => 'idSesion', 'state' => 'Activa'],
];

if (!isset($allowed[$table]) || $id <= 0) {
    header('Location: ' . $back . '?error=' . urlencode('Parámetros inválidos'));
    exit;
}

$idCol = $allowed[$table]['id'];
$stateCol = $allowed[$table]['state'];

// Obtener valor actual
$sql = "SELECT {$stateCol} FROM {$table} WHERE {$idCol} = :id LIMIT 1";
$stmt = $conexion->prepare($sql);
$stmt->execute([':id' => $id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    header('Location: ' . $back . '?error=' . urlencode('Registro no encontrado'));
    exit;
}

$current = (int)$row[$stateCol];
$new = $current ? 0 : 1;

$updateSql = "UPDATE {$table} SET {$stateCol} = :new WHERE {$idCol} = :id";
$upd = $conexion->prepare($updateSql);
$upd->execute([':new' => $new, ':id' => $id]);

$accion = $new ? 'activado' : 'desactivado';
header('Location: ' . $back . '?success=' . urlencode("Registro {$accion} correctamente"));
exit;