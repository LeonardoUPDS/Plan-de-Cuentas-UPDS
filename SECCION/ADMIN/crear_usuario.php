<?php
require_once __DIR__ . '/check_admin.php';
requireAdmin();
require_once __DIR__ . '/../../BD/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['Correo'] ?? '');
    $password = $_POST['Contrasena'] ?? '';
    $idRol = (int)($_POST['idRol'] ?? 2);
    $estado = isset($_POST['Estado']) ? 1 : 0;

    if (!$email || !$password) {
        $error = 'Correo y contraseña son obligatorios.';
    } else {
        $stmt = $conexion->prepare('SELECT idUsuario FROM usuario WHERE Correo = :correo');
        $stmt->execute([':correo' => $email]);
        if ($stmt->fetch()) {
            $error = 'El correo ya está registrado.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $conexion->prepare('INSERT INTO usuario (Correo, Contrasena, idRol, EmailVerificado, Estado) VALUES (:correo, :contrasena, :idRol, 1, :estado)');
            $ins->execute([':correo'=>$email, ':contrasena'=>$hash, ':idRol'=>$idRol, ':estado'=>$estado]);
            header('Location: usuarios.php?success=' . urlencode('Usuario creado'));
            exit;
        }
    }
}

// obtener roles para selector
$roles = $conexion->query('SELECT idRol, Descripcion FROM rol WHERE Estado = 1')->fetchAll(PDO::FETCH_ASSOC);
require_once __DIR__ . '/../../TEMPLATE/header.php';
?>
<div class="container my-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div><span>Crear Usuario</span></div>
      <div><a class="btn btn-secondary btn-sm" href="<?= BASE_URL?>/SECCION/ADMIN/usuarios.php">Volver</a></div>
    </div>
    <div class="card-body">
      <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <form method="POST">
        <div class="mb-3"><label class="form-label">Correo</label><input class="form-control" name="Correo" type="email" required></div>
        <div class="mb-3"><label class="form-label">Contraseña</label><input class="form-control" name="Contrasena" type="password" required></div>
        <div class="mb-3"><label class="form-label">Rol</label>
          <select class="form-control" name="idRol">
            <?php foreach ($roles as $r): ?>
              <option value="<?= (int)$r['idRol'] ?>"><?= htmlspecialchars($r['Descripcion']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="Estado" id="estado" checked><label class="form-check-label" for="estado">Activo</label></div>
        <button class="btn btn-primary">Crear</button>
      </form>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>