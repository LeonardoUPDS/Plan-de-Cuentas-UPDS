<?php
require_once __DIR__ . '/check_admin.php';
requireAdmin();
require_once __DIR__ . '/../../BD/conexion.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: usuarios.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['Correo'] ?? '');
    $idRol = (int)($_POST['idRol'] ?? 2);
    $estado = isset($_POST['Estado']) ? 1 : 0;

    if ($email === '') { $error = 'Correo requerido'; }
    else {
        $stmt = $conexion->prepare('UPDATE usuario SET Correo = :correo, idRol = :idRol, Estado = :estado WHERE idUsuario = :id');
        $stmt->execute([':correo'=>$email, ':idRol'=>$idRol, ':estado'=>$estado, ':id'=>$id]);
        header('Location: usuarios.php?success=' . urlencode('Usuario actualizado'));
        exit;
    }
}

$stmt = $conexion->prepare('SELECT idUsuario, Correo, idRol, Estado FROM usuario WHERE idUsuario = :id');
$stmt->execute([':id'=>$id]);
$u = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$u) { header('Location: usuarios.php?error=' . urlencode('Usuario no encontrado')); exit; }

$roles = $conexion->query('SELECT idRol, Descripcion FROM Rol WHERE Estado = 1')->fetchAll(PDO::FETCH_ASSOC);
require_once __DIR__ . '/../../TEMPLATE/header.php';
?>
<div class="container my-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div><span>Editar Usuario</span></div>
      <div><a class="btn btn-secondary btn-sm" href="<?= BASE_URL?>/SECCION/ADMIN/usuarios.php">Volver</a></div>
    </div>
    <div class="card-body">
      <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <form method="POST">
        <div class="mb-3"><label class="form-label">Correo</label><input class="form-control" name="Correo" value="<?= htmlspecialchars($u['Correo']) ?>" required></div>
        <div class="mb-3"><label class="form-label">Rol</label>
          <select class="form-control" name="idRol">
            <?php foreach ($roles as $r): ?>
              <option value="<?= (int)$r['idRol'] ?>" <?= $r['idRol'] == $u['idRol'] ? 'selected' : '' ?>><?= htmlspecialchars($r['Descripcion']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="Estado" id="estado" <?= $u['Estado'] ? 'checked' : '' ?>><label class="form-check-label" for="estado">Activo</label></div>
        <button class="btn btn-primary">Guardar</button>
      </form>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>