<?php
// Limpia la cache de PHP (OPcache). El servidor no detecta los archivos subidos por FTP,
// asi que despues de cada publicacion hay que limpiarla para que se vean los cambios.
require __DIR__ . '/_inc.php';
requerir_login();
verificar_post();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ok = function_exists('opcache_reset') && opcache_reset();
    flash($ok ? 'Caché limpiada: el sitio ya muestra la última versión publicada.' : 'No se pudo limpiar la caché (OPcache no disponible).', $ok ? 'success' : 'warning');
}
redirigir('/admin/');
