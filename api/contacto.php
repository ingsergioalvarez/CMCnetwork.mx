<?php
// Recibe los formularios del sitio y de la tarjeta QR. Responde JSON.
require __DIR__ . '/../includes/bootstrap.php';
require ROOT . '/includes/datos.php';
require ROOT . '/includes/mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_respuesta(['ok' => false, 'error' => 'Método no permitido'], 405);
}

$campo = function ($nombre, $max) {
    $v = isset($_POST[$nombre]) ? trim((string) $_POST[$nombre]) : '';
    return mb_substr(preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F]/u', '', $v), 0, $max);
};

// Bots: campo trampa lleno o token invalido/demasiado rapido. Respondemos "ok" sin guardar.
if ($campo('sitio_web', 200) !== '') {
    json_respuesta(['ok' => true]);
}
if (!form_token_valido(isset($_POST['token']) ? $_POST['token'] : '')) {
    json_respuesta(['ok' => false, 'error' => 'El formulario expiró. Recarga la página e inténtalo de nuevo.'], 400);
}

$d = [
    'origen'   => $campo('origen', 20) === 'tarjeta' ? 'tarjeta' : 'web',
    'pagina'   => $campo('pagina', 60),
    'nombre'   => $campo('nombre', 120),
    'empresa'  => $campo('empresa', 150),
    'email'    => $campo('email', 150),
    'telefono' => $campo('telefono', 30),
    'interes'  => $campo('interes', 40),
    'mensaje'  => $campo('mensaje', 2000),
];

$errores = [];
if (mb_strlen($d['nombre']) < 2) {
    $errores['nombre'] = 'Escribe tu nombre.';
}
if ($d['email'] !== '' && !filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
    $errores['email'] = 'El correo no es válido.';
}
if (strlen(solo_digitos($d['telefono'])) < 8) {
    $errores['telefono'] = 'Escribe un teléfono válido (al menos 8 dígitos).';
}
if ($d['origen'] === 'web' && $d['email'] === '') {
    $errores['email'] = 'Escribe tu correo.';
}
if (!isset($INTERESES[$d['interes']])) {
    $d['interes'] = '';
}
if ($errores) {
    json_respuesta(['ok' => false, 'error' => 'Revisa los campos marcados.', 'campos' => $errores], 422);
}

$pdo = db();
$ip = ip_cliente();

// Limite: 5 envios por IP cada 10 minutos
$st = $pdo->prepare('SELECT COUNT(*) FROM prospectos WHERE ip = ? AND creado > NOW() - INTERVAL 10 MINUTE');
$st->execute([$ip]);
if ((int) $st->fetchColumn() >= 5) {
    json_respuesta(['ok' => false, 'error' => 'Recibimos varios mensajes desde tu conexión. Intenta en unos minutos.'], 429);
}

// Vendedor y evento: solo para la tarjeta; se validan contra la BD
$vendedor = null;
$evento = null;
if ($d['origen'] === 'tarjeta') {
    $vid = isset($_POST['vendedor']) ? (int) $_POST['vendedor'] : 0;
    $eid = isset($_POST['evento']) ? (int) $_POST['evento'] : 0;
    if ($vid) {
        $st = $pdo->prepare('SELECT id, nombre, email FROM vendedores WHERE id = ?');
        $st->execute([$vid]);
        $vendedor = $st->fetch() ?: null;
    }
    if ($eid) {
        $st = $pdo->prepare('SELECT id, nombre FROM eventos WHERE id = ?');
        $st->execute([$eid]);
        $evento = $st->fetch() ?: null;
    }
}

$pdo->prepare(
    'INSERT INTO prospectos (origen, pagina, nombre, empresa, email, telefono, interes, mensaje, vendedor_id, evento_id, ip, user_agent)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
)->execute([
    $d['origen'], $d['pagina'], $d['nombre'], $d['empresa'], $d['email'], $d['telefono'], $d['interes'], $d['mensaje'],
    $vendedor ? $vendedor['id'] : null, $evento ? $evento['id'] : null,
    $ip, mb_substr(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '', 0, 255),
]);
$id = (int) $pdo->lastInsertId();

// Responder ya al visitante; el correo se envia despues (PHP-FPM)
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['ok' => true, 'mensaje' => '¡Gracias, ' . $d['nombre'] . '! Recibimos tus datos y te contactaremos muy pronto.'], JSON_UNESCAPED_UNICODE);
if (function_exists('fastcgi_finish_request')) {
    fastcgi_finish_request();
}

$asunto = ($d['origen'] === 'tarjeta' ? 'Prospecto de evento' : 'Contacto web') . ': ' . $d['nombre']
    . ($d['empresa'] !== '' ? ' (' . $d['empresa'] . ')' : '');
$html = correo_prospecto([
    'Nombre'   => $d['nombre'],
    'Empresa'  => $d['empresa'],
    'Teléfono' => $d['telefono'],
    'Correo'   => $d['email'],
    'Interés'  => $d['interes'] !== '' ? $INTERESES[$d['interes']] : '',
    'Mensaje'  => $d['mensaje'],
    'Origen'   => $d['origen'] === 'tarjeta' ? 'Tarjeta QR' : 'Sitio web (' . $d['pagina'] . ')',
    'Vendedor' => $vendedor ? $vendedor['nombre'] : '',
    'Evento'   => $evento ? $evento['nombre'] : '',
]);
$para = (array) config('notificar');
if (enviar_correo($para, $asunto, $html, $d['email'] !== '' ? $d['email'] : null)) {
    $pdo->prepare('UPDATE prospectos SET notificado = 1 WHERE id = ?')->execute([$id]);
}
