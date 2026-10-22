<?php
// Descarga el contacto del vendedor como vCard 3.0 (iPhone y Android lo abren en Contactos).
require __DIR__ . '/../includes/bootstrap.php';
require ROOT . '/includes/datos.php';
require ROOT . '/includes/tarjeta.php';

$vendedor = tarjeta_vendedor(isset($_GET['v']) ? slugify($_GET['v']) : '');
$v = tarjeta_datos($vendedor, $CONTACTO);

$esc = function ($s) {
    return str_replace(["\\", "\n", ',', ';'], ["\\\\", '\n', '\,', '\;'], (string) $s);
};

// Nombres mexicanos: con 3 o mas palabras, las dos ultimas son los apellidos
$partes = preg_split('/\s+/', trim($v['nombre']));
$n_apellidos = count($partes) >= 3 ? 2 : (count($partes) === 2 ? 1 : 0);
$apellido = implode(' ', array_splice($partes, count($partes) - $n_apellidos));
$nombre = implode(' ', $partes);

$lineas = [
    'BEGIN:VCARD',
    'VERSION:3.0',
    'N:' . $esc($apellido) . ';' . $esc($nombre) . ';;;',
    'FN:' . $esc($v['nombre']),
    'ORG:CMC Service Network',
    'TITLE:' . $esc($v['puesto']),
];
if ($vendedor) {
    // Tarjeta de un vendedor: solo su celular (con lada internacional) y su correo.
    // Sin telefonos ni direccion de oficina: en ferias fuera de la CDMX confunden al prospecto.
    if ($v['telefono'] !== '') {
        $lineas[] = 'TEL;TYPE=CELL,VOICE,PREF:+' . wa_numero($v['telefono']);
    }
} else {
    foreach ($CONTACTO['telefonos'] as $t) {
        $lineas[] = 'TEL;TYPE=WORK,VOICE:' . $esc($t['tel']);
    }
}
if ($v['email'] !== '') {
    $lineas[] = 'EMAIL;TYPE=INTERNET,WORK:' . $esc($v['email']);
}
$lineas[] = 'URL:' . config('url');
$lineas[] = 'NOTE:' . $esc('CMC Service Network - Un solo socio tecnológico');

if ($vendedor && !empty($vendedor['foto'])) {
    $tipo = strpos($vendedor['foto_tipo'], 'png') !== false ? 'PNG' : 'JPEG';
    $lineas[] = 'PHOTO;ENCODING=b;TYPE=' . $tipo . ':' . base64_encode($vendedor['foto']);
}
$lineas[] = 'END:VCARD';

// Plegado de lineas a 75 octetos (RFC 2425)
$salida = '';
foreach ($lineas as $l) {
    while (strlen($l) > 75) {
        $corte = 75;
        while ($corte > 0 && (ord($l[$corte]) & 0xC0) === 0x80) { // no partir caracteres UTF-8
            $corte--;
        }
        $salida .= substr($l, 0, $corte) . "\r\n";
        $l = ' ' . substr($l, $corte);
    }
    $salida .= $l . "\r\n";
}

$archivo = slugify($v['nombre']) ?: 'contacto-cmc';
header('Content-Type: text/vcard; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $archivo . '.vcf"');
header('Cache-Control: no-store');
echo $salida;
