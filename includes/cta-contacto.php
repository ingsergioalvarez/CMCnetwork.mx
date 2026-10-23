<?php
// Bloque final de las paginas: lleva a /contacto.php. Variable opcional: $interes (opcion preseleccionada).
defined('CMC') || exit;
require_once __DIR__ . '/datos.php';
$wa = 'https://wa.me/' . $CONTACTO['whatsapp'] . '?text=' . rawurlencode($CONTACTO['whatsapp_msg']);
?>
<section class="seccion seccion-noche cta-contacto">
  <div class="container text-center revelar">
    <span class="etiqueta">Contacto</span>
    <h2>Hablemos de tu proyecto</h2>
    <p>Cuéntanos qué necesitas y un asesor te ayudará a definir el alcance de tu solución.</p>
    <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
      <a href="<?= e(contacto_url(isset($interes) ? $interes : '')) ?>" class="btn btn-cmc">Contáctanos <?= icono('arrow-right') ?></a>
      <a href="<?= e($wa) ?>" class="btn btn-borde" target="_blank" rel="noopener"><?= icono('whatsapp') ?> WhatsApp</a>
    </div>
    <p class="cta-tels"><?php foreach ($CONTACTO['telefonos'] as $i => $t): ?><?= $i ? ' · ' : '' ?><a href="tel:<?= e($t['tel']) ?>"><?= e($t['texto']) ?></a><?php endforeach; ?></p>
  </div>
</section>
