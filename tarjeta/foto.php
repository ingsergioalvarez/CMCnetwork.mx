<?php
// Sirve la foto del vendedor guardada en la BD.
require __DIR__ . '/../includes/bootstrap.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$st = db()->prepare('SELECT foto, foto_tipo FROM vendedores WHERE id = ? AND foto IS NOT NULL');
$st->execute([$id]);
$f = $st->fetch();
if (!$f) {
    http_response_code(404);
    exit;
}
$etag = '"' . md5($f['foto']) . '"';
header('ETag: ' . $etag);
header('Cache-Control: public, max-age=3600');
if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) === $etag) {
    http_response_code(304);
    exit;
}
header('Content-Type: ' . $f['foto_tipo']);
header('Content-Length: ' . strlen($f['foto']));
echo $f['foto'];
