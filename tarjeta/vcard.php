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

$lineas = [
    'BEGIN:VCARD',
    'VERSION:3.0',
    'N:;' . $esc($v['nombre']) . ';;;', // nombre de marca: sin separar apellidos
    'FN:' . $esc($v['nombre']),
    'ORG:CMC Service Network',
    'TITLE:' . $esc($v['puesto']),
];
// Solo el celular (con lada internacional) y el sitio web: sin correo ni telefonos de oficina.
// Sin vendedor se usa el celular de WhatsApp de ventas.
$celular = $vendedor ? ($v['telefono'] !== '' ? $v['telefono'] : $v['whatsapp']) : $v['whatsapp'];
if ($celular !== '') {
    $lineas[] = 'TEL;TYPE=CELL,VOICE,PREF:+' . wa_numero($celular);
}
$lineas[] = 'URL:https://cmcnetwork.mx';
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
