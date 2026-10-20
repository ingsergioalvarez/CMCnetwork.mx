<?php
// Genera un config.php a partir de .ftp.env (sin mostrar contrasenas).
// Uso: php scripts/generar-config.php <salida> <url> [debug]
//   php scripts/generar-config.php config/config.php http://localhost:8000 debug
//   php scripts/generar-config.php build/config-servidor.php https://cmcnetwork.mx
$raiz = dirname(__DIR__);
$salida = isset($argv[1]) ? $argv[1] : 'config/config.php';
$url = isset($argv[2]) ? $argv[2] : 'http://localhost:8000';

$env = [];
foreach (file($raiz . '/.ftp.env', FILE_IGNORE_NEW_LINES) as $linea) {
    if (preg_match('/^\s*([^#=]+)=(.*)$/', $linea, $m)) {
        $env[trim($m[1])] = trim($m[2]);
    }
}

$cfg = require $raiz . '/config/config.example.php';
$cfg['db'] = [
    'host' => $env['DB_HOST'],
    'port' => (int) $env['DB_PORT'],
    'name' => $env['DB_NAME'],
    'user' => $env['DB_USER'],
    'pass' => $env['DB_PASS'],
];
foreach (['SMTP_HOST' => 'smtp_host', 'SMTP_PORT' => 'smtp_port', 'SMTP_USER' => 'smtp_user', 'SMTP_PASS' => 'smtp_pass'] as $k => $c) {
    if (isset($env[$k])) {
        $cfg['mail'][$c] = $k === 'SMTP_PORT' ? (int) $env[$k] : $env[$k];
    }
}
$cfg['secret'] = isset($env['APP_SECRET']) ? $env['APP_SECRET'] : bin2hex(random_bytes(32));
$cfg['url'] = $url;
$cfg['debug'] = isset($argv[3]) && $argv[3] === 'debug';

if (!is_dir(dirname($salida))) {
    mkdir(dirname($salida), 0755, true);
}
file_put_contents($salida, "<?php\n// Generado por scripts/generar-config.php — NO subir a GitHub\nreturn " . var_export($cfg, true) . ";\n");
echo "Config escrito en $salida\n";
