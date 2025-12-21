<?php
// true = PRODUCCIÓN (InfinityFree)
// false = LOCAL (XAMPP)
define('PRODUCCION', true);

if (PRODUCCION) {
    // 🌍 INFINITYFREE
    define('DB_HOST', 'sql211.infinityfree.com');
    define('DB_USER', 'if0_40675623');
    define('DB_PASS', 'LdVmLeonardo78');
    define('DB_NAME', 'if0_40675623_PlataformaEducativa');

    define('BASE_URL', 'https://planinvencible777.page.gd');
} else {
    // 🖥️ LOCALHOST
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'PlataformaEducativa');

    define('BASE_URL', 'http://localhost:8080/Plan-de-cuentas-UPDS');
}

if(PRODUCCION) {
    $tablaUsuario = "usuario";
    $tablaRol = "rol";
    $tablaPermiso = "permiso";
    $tablaToken = "tokenrecuperacion";
} else {
    $tablaUsuario = "usuario";
    $tablaRol = "rol";
    $tablaPermiso = "permiso";
    $tablaToken = "tokenrecuperacion";
}
