<?php
require_once __DIR__ . '/../../BD/conexion.php';
require_once __DIR__ . '/../../TEMPLATE/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = 'Correo y contraseña son obligatorios.';
    } else {
        // Comprobar si ya existe el correo
        $stmt = $conexion->prepare('SELECT idUsuario FROM usuario WHERE Correo = :correo');
        $stmt->execute([':correo' => $email]);
        if ($stmt->fetch()) {
            $error = 'El correo ya está registrado.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $estado = 1; // activo por defecto
            $idRol = 2; // invitado por defecto
            $ins = $conexion->prepare('INSERT INTO usuario (Correo, Contrasena, Estado, idRol) VALUES (:correo, :contrasena, :estado, :idRol)');
            $ins->execute([
                ':correo' => $email,
                ':contrasena' => $hash,
                ':estado' => $estado,
                ':idRol' => $idRol
            ]);
            header('Location: index.php?mensaje=' . urlencode('Usuario creado'));
            exit;
        }
    }
}
?>
<div class="container my-4">
    <div class="card">
        <div class="card-header">Crear Usuario</div>
        <div class="card-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form action="" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>
