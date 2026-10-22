<?php
// Iconos SVG en linea (Bootstrap Icons). Cada pagina incluye solo los simbolos que uso.
// Para agregar iconos nuevos: usarlos con icono('nombre') y correr scripts/generar-iconos.php.
defined('CMC') || exit;

$ICONOS_USADOS = [];

/** SVG de un icono. $nombre acepta 'camera' o 'bi-camera'. */
function icono($nombre, $clase = '')
{
    global $ICONOS_USADOS;
    $n = preg_replace('/^bi-/', '', $nombre);
    $ICONOS_USADOS[$n] = true;
    return '<svg class="bi' . ($clase !== '' ? ' ' . e($clase) : '') . '" aria-hidden="true" focusable="false"><use href="#bi-' . e($n) . '"/></svg>';
}

/** Definiciones (<symbol>) de los iconos usados en la pagina. Va al final del <body>. */
function sprite_iconos()
{
    global $ICONOS_USADOS;
    if (!$ICONOS_USADOS) return '';
    $svg = require __DIR__ . '/iconos-svg.php';
    $out = '<svg xmlns="http://www.w3.org/2000/svg" style="display:none">';
    foreach (array_keys($ICONOS_USADOS) as $n) {
        if (isset($svg[$n])) $out .= '<symbol id="bi-' . $n . '" viewBox="0 0 16 16">' . $svg[$n] . '</symbol>';
    }
    return $out . '</svg>';
}
