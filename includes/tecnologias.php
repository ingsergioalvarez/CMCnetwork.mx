<?php
// Seccion de tecnologias de conectividad en carrusel "coverflow": la tarjeta activa queda al centro
// y al frente; las demas se asoman detras, atenuadas. Logica en js/app.js (data-coverflow).
defined('CMC') || exit;
require_once __DIR__ . '/datos.php';

$wa_base = 'https://wa.me/' . $CONTACTO['whatsapp'] . '?text=';
?>
<section id="tecnologias" class="seccion seccion-clara">
  <div class="container">
    <div class="titulo-seccion text-center revelar">
      <span class="etiqueta">Tecnologías</span>
      <h2>Una red, cuatro tecnologías, siempre conectado</h2>
      <p>Combinamos fibra óptica, microondas, LTE y satelital según tu sitio, y las usamos como respaldo entre sí para que tu operación nunca se detenga.</p>
    </div>

    <div class="coverflow" data-coverflow role="region" aria-roledescription="carrusel" aria-label="Tecnologías de conectividad">
      <div class="coverflow-escena">
        <?php $i = 0; foreach ($TECNOLOGIAS as $clave => $t): ?>
          <article class="tec" id="tec-<?= e($clave) ?>" data-clave="<?= e($clave) ?>" aria-roledescription="tarjeta" aria-label="<?= ($i + 1) . ' de ' . count($TECNOLOGIAS) . ': ' . e($t['titulo']) ?>">
            <div class="tec-img">
              <img src="/img/tecnologias/<?= e($clave) ?>-400.webp" srcset="/img/tecnologias/<?= e($clave) ?>-400.webp 400w, /img/tecnologias/<?= e($clave) ?>-640.webp 640w"
                   sizes="(max-width: 767px) 92vw, 340px" alt="<?= e($t['titulo']) ?>" width="640" height="800" loading="lazy">
            </div>
            <div class="tec-cuerpo">
              <div class="tec-cabeza">
                <span class="tec-icono"><?= icono($t['icono']) ?></span>
                <div>
                  <span class="tec-insignia"><?= e($t['insignia']) ?></span>
                  <h3><?= e($t['titulo']) ?></h3>
                </div>
              </div>
              <p class="tec-texto"><?= e($t['texto']) ?></p>
              <ul class="tec-puntos">
                <?php foreach ($t['puntos'] as $p): ?><li><?= icono('check-circle') ?><?= e($p) ?></li><?php endforeach; ?>
              </ul>
              <p class="tec-ideal"><strong>Ideal para:</strong> <?= e($t['ideal']) ?></p>
              <div class="tec-botones">
                <a href="#contacto" class="btn btn-cmc btn-sm" data-cotizar="<?= e($t['titulo']) ?>">Cotizar <?= icono('arrow-right') ?></a>
                <a href="<?= e($wa_base . rawurlencode('Hola, estoy buscando información de CMC Service Network sobre ' . mb_strtolower($t['titulo']) . '.')) ?>" class="btn btn-borde btn-sm" target="_blank" rel="noopener"><?= icono('whatsapp') ?> Preguntar</a>
              </div>
            </div>
          </article>
        <?php $i++; endforeach; ?>
      </div>
      <button type="button" class="carrusel-btn coverflow-btn coverflow-prev" data-dir="-1" aria-label="Tecnología anterior"><?= icono('chevron-left') ?></button>
      <button type="button" class="carrusel-btn coverflow-btn coverflow-next" data-dir="1" aria-label="Tecnología siguiente"><?= icono('chevron-right') ?></button>
      <div class="carrusel-puntos coverflow-puntos"></div>
    </div>
  </div>
</section>
