<?php include "../TEMPLATE/header.php"; ?>

<div class="container my-4">
    <div class="card" style="max-width:480px; margin:0 auto;">
        <div class="card-body">
            <h4 class="card-title">Recuperar contraseña</h4>
            <form method="POST" action="recuperar_password_process.php">
                <div class="mb-3">
                    <input type="email" class="form-control" name="correo" placeholder="Correo electrónico" required>
                </div>
                <div class="d-grid">
                    <button class="btn btn-primary">Enviar enlace de recuperación</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "../TEMPLATE/footer.php"; ?>
