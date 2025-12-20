<?php
function registrarSesion($conexion, $idUsuario) {

    $tokenSesion = bin2hex(random_bytes(32));
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'DESCONOCIDA';
    $navegador = $_SERVER['HTTP_USER_AGENT'] ?? 'DESCONOCIDO';

    $sql = "
        INSERT INTO SesionUsuario 
        (idUsuario, TokenSesion, IP, Navegador, Activa)
        VALUES (:idUsuario, :token, :ip, :nav, 1)
    ";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([
        ':idUsuario' => $idUsuario,
        ':token' => $tokenSesion,
        ':ip' => $ip,
        ':nav' => $navegador
    ]);

    $_SESSION['tokenSesion'] = $tokenSesion;
}
