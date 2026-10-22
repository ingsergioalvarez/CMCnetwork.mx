<?php
// CSS y JS en linea. El hosting comprime el HTML pero no los .css/.js ni les pone cache,
// asi que incrustarlos sale mas ligero y ahorra peticiones que bloquean la carga.
defined('CMC') || exit;

/** Contenido minificado de varios archivos de /css, listo para un <style>. */
function css_en_linea(array $archivos)
{
    $css = '';
    foreach ($archivos as $a) {
        $css .= file_get_contents(ROOT . '/css/' . $a) . "\n";
    }
    $css = preg_replace('#/\*.*?\*/#s', '', $css);         // comentarios
    $css = preg_replace('/\s+/', ' ', $css);                // espacios y saltos
    $css = preg_replace('/\s*([{};,])\s*/', '$1', $css);    // alrededor de { } ; ,
    $css = preg_replace('/:\s+/', ':', $css);               // "color: x" -> "color:x"
    return str_replace(';}', '}', trim($css));
}

/** Contenido de un archivo de /js para un <script> en linea. */
function js_en_linea($archivo)
{
    $js = file_get_contents(ROOT . '/js/' . $archivo);
    // Quita comentarios de linea completa y la sangria (sin tocar el codigo)
    $js = preg_replace('#^\s*//.*$#m', '', $js);
    $js = preg_replace('/^[ \t]+/m', '', $js);
    return preg_replace('/\n{2,}/', "\n", $js);
}
