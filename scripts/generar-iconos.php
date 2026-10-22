<?php
// Genera includes/iconos-svg.php con solo los Bootstrap Icons que usa el sitio publico.
// Uso: php scripts/generar-iconos.php <carpeta icons de bootstrap-icons>
//   (npm pack bootstrap-icons@1.11.3 y descomprimir; la carpeta es package/icons)
if (PHP_SAPI !== 'cli') exit;
$dir = isset($argv[1]) ? rtrim($argv[1], '/\\') : '';
if (!is_dir($dir)) {
    fwrite(STDERR, "Indica la carpeta icons de bootstrap-icons\n");
    exit(1);
}
$raiz = dirname(__DIR__);
$archivos = array_merge(glob("$raiz/*.php"), glob("$raiz/includes/*.php"), glob("$raiz/tarjeta/*.php"));

$nombres = [];
foreach ($archivos as $f) {
    if (basename($f) === 'iconos.php') continue;
    $txt = file_get_contents($f);
    preg_match_all("/icono\\('([a-z0-9-]+)'/", $txt, $m1);
    preg_match_all("/'bi-([a-z0-9-]+)'/", $txt, $m2);
    foreach (array_merge($m1[1], $m2[1]) as $n) $nombres[$n] = true;
}
ksort($nombres);

$salida = "<?php\n// Generado por scripts/generar-iconos.php (Bootstrap Icons 1.11.3, licencia MIT). No editar a mano.\nreturn [\n";
foreach (array_keys($nombres) as $n) {
    $svg = @file_get_contents("$dir/$n.svg");
    if ($svg === false) {
        fwrite(STDERR, "No existe el icono: $n\n");
        continue;
    }
    // Solo el contenido interno del <svg>, en una linea
    $interno = trim(preg_replace('#^.*?<svg[^>]*>|</svg>\s*$#s', '', $svg));
    $interno = preg_replace('/\s*\n\s*/', '', $interno);
    $salida .= '    ' . var_export($n, true) . ' => ' . var_export($interno, true) . ",\n";
}
$salida .= "];\n";
file_put_contents("$raiz/includes/iconos-svg.php", $salida);
echo count($nombres) . " iconos\n";
