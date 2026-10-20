<?php
// Variables esperadas: $titulo, $descripcion, $pagina (inicio|corporativo|residencial|tarifas)
defined('CMC') || exit;
require_once __DIR__ . '/datos.php';

$titulo = isset($titulo) ? $titulo : 'CMC Service Network';
$descripcion = isset($descripcion) ? $descripcion : 'Conectividad empresarial y residencial: Internet dedicado, respaldo 4G/5G, satelital e Internet para eventos.';
$pagina = isset($pagina) ? $pagina : '';

$menu = [
    'inicio'      => ['/', 'Inicio'],
    'corporativo' => ['/corporativo.php', 'Corporativo'],
    'residencial' => ['/residencial.php', 'Residencial'],
    'tarifas'     => ['/tarifas.php', 'Tarifas'],
    'cobertura'   => ['/Red-CMC/mapadered.html', 'Cobertura'],
];
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
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="/css/marca.css">
  <link rel="stylesheet" href="/css/cmc.css?v=4">
</head>
<body class="pagina-<?= e($pagina) ?>">

<nav class="navbar navbar-expand-lg navbar-dark fixed-top cmc-nav" id="nav">
  <div class="container">
    <a class="navbar-brand" href="/"><img src="/imagenes/marca/logo-cmc-blanco-web.png" alt="CMC Service Network" width="155" height="44"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Abrir menú">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menu">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <?php foreach ($menu as $clave => $item): ?>
          <li class="nav-item"><a class="nav-link<?= $clave === $pagina ? ' active' : '' ?>" href="<?= e($item[0]) ?>"><?= e($item[1]) ?></a></li>
        <?php endforeach; ?>
        <li class="nav-item ms-lg-3"><a class="btn btn-cmc btn-sm" href="#contacto">Contáctanos</a></li>
      </ul>
    </div>
  </div>
</nav>

<a href="<?= e($wa) ?>" class="whatsapp-flotante" target="_blank" rel="noopener" aria-label="Escríbenos por WhatsApp"><i class="bi bi-whatsapp"></i></a>
