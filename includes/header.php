<?php
// Variables esperadas: $titulo, $descripcion, $pagina (inicio|residencial|clave de un pilar)
// y opcional $precargar (imagen de la portada, para pedirla antes que el CSS la descubra).
defined('CMC') || exit;
require_once __DIR__ . '/datos.php';
require_once __DIR__ . '/recursos.php';

$titulo = isset($titulo) ? $titulo : 'CMC Service Network';
$descripcion = isset($descripcion) ? $descripcion : 'Un solo socio tecnológico: IoT y automatización, videovigilancia con IA, RFID y cadena de suministro, conectividad de misión crítica, ciberseguridad y software.';
$pagina = isset($pagina) ? $pagina : '';

// El pilar destacado va directo en el menu; los demas en el desplegable "Soluciones"
$menu_soluciones = array_diff_key($PILARES, ['iot' => 1]);
$wa = 'https://wa.me/' . $CONTACTO['whatsapp'] . '?text=' . rawurlencode($CONTACTO['whatsapp_msg']);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($titulo) ?></title>
  <meta name="description" content="<?= e($descripcion) ?>">
  <meta name="theme-color" content="#06123D">
  <meta property="og:title" content="<?= e($titulo) ?>">
  <meta property="og:description" content="<?= e($descripcion) ?>">
  <meta property="og:image" content="<?= e(config('url')) ?>/imagenes/marca/logo-cmc-fondo.jpg">
  <link rel="icon" href="/imagenes/marca/favicon.ico">
  <link rel="apple-touch-icon" href="/imagenes/marca/apple-touch-icon.png">
  <link rel="preload" href="/fonts/poppins-700.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="/fonts/poppins-400.woff2" as="font" type="font/woff2" crossorigin>
  <?php if (!empty($precargar)): ?><link rel="preload" href="<?= e($precargar) ?>" as="image" type="image/webp" fetchpriority="high"><?php endif; ?>
  <style><?= css_en_linea(['bootstrap-sitio.css', 'fuentes.css', 'marca.css', 'cmc.css']) ?></style>
</head>
<body class="pagina-<?= e($pagina) ?>">

<nav class="navbar navbar-expand-lg navbar-dark fixed-top cmc-nav" id="nav">
  <div class="container">
    <a class="navbar-brand" href="/"><img src="/imagenes/marca/logo-cmc-nav.png" alt="CMC Service Network" width="155" height="44"></a>
    <button class="navbar-toggler" type="button" aria-controls="menu" aria-expanded="false" aria-label="Abrir menú">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menu">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item"><a class="nav-link<?= $pagina === 'inicio' ? ' active' : '' ?>" href="/">Inicio</a></li>
        <li class="nav-item"><a class="nav-link<?= $pagina === 'iot' ? ' active' : '' ?>" href="<?= e($PILARES['iot']['url']) ?>"><?= e($PILARES['iot']['nombre']) ?></a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle<?= isset($menu_soluciones[$pagina]) ? ' active' : '' ?>" href="#" role="button" aria-expanded="false">Soluciones</a>
          <ul class="dropdown-menu dropdown-menu-dark">
            <?php foreach ($menu_soluciones as $clave => $mp): ?>
              <li><a class="dropdown-item<?= $clave === $pagina ? ' active' : '' ?>" href="<?= e($mp['url']) ?>"><?= icono($mp['icono'], 'me-2') ?><?= e($mp['nombre']) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </li>
        <li class="nav-item ms-lg-3"><a class="btn btn-cmc btn-sm" href="#contacto">Contáctanos</a></li>
      </ul>
    </div>
  </div>
</nav>

<a href="<?= e($wa) ?>" class="whatsapp-flotante" target="_blank" rel="noopener" aria-label="Escríbenos por WhatsApp"><?= icono('whatsapp') ?></a>

<main id="contenido">
