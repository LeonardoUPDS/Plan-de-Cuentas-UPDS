<?php 
require_once(__DIR__ . "/../../BD/conexion.php"); 
require_once(__DIR__ . "/../../TEMPLATE/header.php");
?>
<?php
// Selección de usuarios usando columnas que existen en la BD (ver BD/BaseDatos.sql)
$stmt = $conexion->query(
    "SELECT u.idUsuario, u.Correo, r.Descripcion AS Rol, IF(u.Estado=1,'Activo','Inactivo') AS Estado
     FROM usuario u
     LEFT JOIN rol r ON u.idRol = r.idRol"
);

$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container my-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Listado de Usuarios</span>
            <a href="crear.php" class="btn btn-primary btn-sm">Crear Usuario</a>
        </div>
        <div class="card-body">
    <?php if (!empty($_GET['mensaje'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['mensaje']); ?></div>
    <?php endif; ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($usuarios) > 0): ?>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= htmlspecialchars($usuario['idUsuario']) ?></td>
                        <td><?= htmlspecialchars($usuario['Correo']) ?></td>
                        <td><?= htmlspecialchars($usuario['Rol'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($usuario['Estado']) ?></td>
                        <td>
                            <a href="editar.php?id=<?= urlencode($usuario['idUsuario']) ?>" class="btn btn-warning btn-sm">Editar</a>
                            <?php if ($usuario['Estado'] == 'Activo'): ?>
                                <a href="toggle_estado.php?id=<?= urlencode($usuario['idUsuario']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Desactivar usuario?');">Desactivar</a>
                            <?php else: ?>
                                <a href="toggle_estado.php?id=<?= urlencode($usuario['idUsuario']) ?>" class="btn btn-success btn-sm" onclick="return confirm('¿Activar usuario?');">Activar</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-center">No hay usuarios registrados</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
        </div>
        <div class="card-footer text-muted">Total usuarios: <?= count($usuarios) ?></div>
    </div>
</div>
