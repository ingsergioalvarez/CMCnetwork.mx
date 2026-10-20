<?php
// Crea (o restablece) un usuario del panel. Uso:
//   php scripts/crear-admin.php <usuario> "<Nombre>"
// La contrasena se genera aleatoria y se muestra una sola vez.
if (PHP_SAPI !== 'cli') {
    exit;
}
require __DIR__ . '/../includes/bootstrap.php';

$usuario = isset($argv[1]) ? strtolower($argv[1]) : '';
$nombre = isset($argv[2]) ? $argv[2] : '';
if (!preg_match('/^[a-z0-9._-]{3,60}$/', $usuario)) {
    exit("Uso: php scripts/crear-admin.php <usuario> \"<Nombre>\"\n");
}
$pass = substr(strtr(base64_encode(random_bytes(12)), '+/', 'Kx'), 0, 14);
$hash = password_hash($pass, PASSWORD_DEFAULT);
db()->prepare('INSERT INTO usuarios_admin (usuario, nombre, password_hash) VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash), nombre = VALUES(nombre)')->execute([$usuario, $nombre, $hash]);
echo "Usuario: $usuario\nContrasena: $pass\n(Cambiala al entrar en Panel > Usuarios)\n";
