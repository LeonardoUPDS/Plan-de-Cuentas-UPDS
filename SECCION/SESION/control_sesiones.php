<?php
function controlarSesiones($conexion, $idUsuario) {

    // 1. Obtener el plan activo del usuario
    $sql = "
        SELECT p.MaxSesiones
        FROM Suscripcion s
        JOIN Plan p ON s.idPlan = p.idPlan
        WHERE s.idUsuario = :idUsuario
          AND s.Estado = 1
          AND (s.FechaFin IS NULL OR s.FechaFin >= NOW())
        LIMIT 1
    ";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':idUsuario' => $idUsuario]);
    $plan = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$plan) {
        return; // sin plan → no controla sesiones
    }

    $maxSesiones = (int)$plan['MaxSesiones'];

    // proteger contra valores no válidos
    if ($maxSesiones <= 0) return;

    // 2. Contar sesiones activas
    $sql = "
        SELECT idSesion
        FROM SesionUsuario
        WHERE idUsuario = :idUsuario
          AND Activa = 1
        ORDER BY FechaInicio ASC
    ";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':idUsuario' => $idUsuario]);
    $sesiones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. Si excede el límite → cerrar sesiones antiguas
    while (count($sesiones) >= $maxSesiones) {
        $sesionAntigua = array_shift($sesiones);

        $sql = "
            UPDATE SesionUsuario
            SET Activa = 0
            WHERE idSesion = :idSesion
        ";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([':idSesion' => $sesionAntigua['idSesion']]);
    }
}
