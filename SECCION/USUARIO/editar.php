<?php
require_once __DIR__ . '/../../BD/conexion.php';
require_once __DIR__ . '/../../TEMPLATE/header.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

// Obtener usuario
$stmt = $conexion->prepare('SELECT idUsuario, Correo FROM usuario WHERE idUsuario = :id');
$stmt->execute([':id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email) {
        $error = 'El correo es obligatorio.';
    } else {
        // comprobar si existe otro usuario con el mismo correo
        $check = $conexion->prepare('SELECT idUsuario FROM usuario WHERE Correo = :correo AND idUsuario != :id');
        $check->execute([':correo' => $email, ':id' => $id]);
        if ($check->fetch()) {
            $error = 'El correo ya está en uso por otro usuario.';
        } else {
            if (!empty($password)) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $upd = $conexion->prepare('UPDATE usuario SET Correo = :correo, Contrasena = :contrasena WHERE idUsuario = :id');
                $upd->execute([':correo' => $email, ':contrasena' => $hash, ':id' => $id]);
            } else {
                $upd = $conexion->prepare('UPDATE usuario SET Correo = :correo WHERE idUsuario = :id');
                $upd->execute([':correo' => $email, ':id' => $id]);
            }
            header('Location: index.php?mensaje=' . urlencode('Usuario actualizado'));
            exit;
        }
    }
}

?>
<div class="container my-4">
    <div class="card">
        <div class="card-header">Editar Usuario</div>
        <div class="card-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form action="" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['Correo']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Nueva contraseña (dejar vacío para mantener la actual)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>