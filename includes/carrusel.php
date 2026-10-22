<?php
// Carrusel reutilizable (scroll-snap + js/app.js). Cada item es HTML ya escapado.
defined('CMC') || exit;

/**
 * Imprime un carrusel.
 * $items: lista de strings HTML (una tarjeta cada uno).
 * $opciones: 'por_vista' => 3|4 (escritorio), 'auto' => bool (avance automatico), 'etiqueta' => texto para lectores de pantalla.
 */
function carrusel(array $items, array $opciones = [])
{
    $por_vista = isset($opciones['por_vista']) ? (int) $opciones['por_vista'] : 3;
    $auto = !empty($opciones['auto']);
    $etiqueta = isset($opciones['etiqueta']) ? $opciones['etiqueta'] : 'Carrusel';
    ?>
    <div class="carrusel" data-carrusel<?= $auto ? ' data-auto="6000"' : '' ?> style="--por-vista:<?= $por_vista ?>" role="region" aria-roledescription="carrusel" aria-label="<?= e($etiqueta) ?>">
      <div class="carrusel-pista" tabindex="0">
        <?php foreach ($items as $html): ?>
          <div class="carrusel-item"><?= $html ?></div>
        <?php endforeach; ?>
      </div>
      <div class="carrusel-controles">
        <button type="button" class="carrusel-btn" data-dir="-1" aria-label="Anterior"><i class="bi bi-chevron-left"></i></button>
        <div class="carrusel-puntos"></div>
        <button type="button" class="carrusel-btn" data-dir="1" aria-label="Siguiente"><i class="bi bi-chevron-right"></i></button>
      </div>
    </div>
    <?php
}

/** Tarjeta con imagen de una solucion. $enlace: a donde lleva (por defecto, su detalle). */
function tarjeta_solucion($slug, array $s, $enlace = null)
{
    global $PILARES;
    $enlace = $enlace ?: solucion_url($slug);
    ob_start(); ?>
    <a class="tarjeta-sol" href="<?= e($enlace) ?>" data-pilar="<?= e($s['pilar']) ?>">
      <span class="tarjeta-sol-img"><img src="/img/soluciones/<?= e($slug) ?>.jpg" alt="<?= e($s['nombre'] . ': ' . $s['sub']) ?>" loading="lazy" width="480" height="360"></span>
      <span class="tarjeta-sol-cuerpo">
        <span class="tarjeta-sol-pilar"><i class="bi <?= e($s['icono']) ?>"></i> <?= e($PILARES[$s['pilar']]['nombre']) ?></span>
        <strong><?= e($s['nombre']) ?></strong>
        <span class="tarjeta-sol-sub"><?= e($s['sub']) ?></span>
        <span class="tarjeta-sol-texto"><?= e($s['resumen']) ?></span>
        <span class="tarjeta-sol-mas">Conocer más <i class="bi bi-arrow-right"></i></span>
      </span>
    </a>
    <?php return ob_get_clean();
}

/** Tarjeta de industria (icono + texto). */
function tarjeta_industria(array $i)
{
    ob_start(); ?>
    <div class="tarjeta-ind">
      <div class="icono"><i class="bi <?= e($i['icono']) ?>"></i></div>
      <h3><?= e($i['nombre']) ?></h3>
      <p><?= e($i['texto']) ?></p>
    </div>
    <?php return ob_get_clean();
}
