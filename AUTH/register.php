<?php include "../TEMPLATE/header.php"; ?>

<div class="container my-4">
    <div class="card" style="max-width:480px; margin:0 auto;">
        <div class="card-body">
            <h4 class="card-title">Registrarse</h4>
            <form method="POST" action="register_process.php">
                <div class="mb-3">
                    <input type="email" class="form-control" name="correo" placeholder="Correo" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="contrasena" placeholder="Contraseña" required>
                </div>
                <div class="d-grid">
                    <button class="btn btn-primary">Registrarse</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "../TEMPLATE/footer.php"; ?>
