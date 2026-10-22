<?php
// Seccion de certificaciones y alianzas en carrusel. Requiere carrusel.php ya cargado.
defined('CMC') || exit;
require_once __DIR__ . '/datos.php';

$logos = array_map(function ($c) {
    return '<div class="logo-cert"><img src="/img/certificaciones/' . e($c['archivo']) . '" alt="' . e($c['nombre']) . '" title="' . e($c['nombre']) . '" loading="lazy"></div>';
}, $CERTIFICACIONES);
?>
<section class="seccion seccion-certs">
  <div class="container">
    <div class="titulo-seccion text-center revelar">
      <span class="etiqueta">Certificaciones y alianzas</span>
      <h2>Respaldados por las marcas líderes</h2>
      <p>Somos socios certificados de los principales fabricantes de identificación, impresión y captura de datos.</p>
    </div>
    <?php carrusel($logos, ['por_vista' => 5, 'auto' => true, 'etiqueta' => 'Certificaciones y alianzas']); ?>
  </div>
</section>
