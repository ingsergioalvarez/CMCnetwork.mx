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
        <button type="button" class="carrusel-btn" data-dir="-1" aria-label="Anterior"><?= icono('chevron-left') ?></button>
        <div class="carrusel-puntos"></div>
        <button type="button" class="carrusel-btn" data-dir="1" aria-label="Siguiente"><?= icono('chevron-right') ?></button>
      </div>
    </div>
    <?php
}

/**
 * <img> de una solucion en WebP con dos tamanos (480 y 960 px).
 * $tamanos: atributo sizes, segun el ancho con el que se muestra.
 */
function img_solucion($slug, $alt, $tamanos, $atributos = 'loading="lazy"')
{
    $base = '/img/soluciones/' . e($slug);
    return '<img src="' . $base . '-480.webp" srcset="' . $base . '-480.webp 480w, ' . $base . '.webp 960w" sizes="' . e($tamanos) . '"'
        . ' alt="' . e($alt) . '" width="960" height="720" ' . $atributos . '>';
}

/** Tarjeta con imagen de una solucion. $enlace: a donde lleva (por defecto, su detalle). */
function tarjeta_solucion($slug, array $s, $enlace = null)
{
    global $PILARES;
    $enlace = $enlace ?: solucion_url($slug);
    ob_start(); ?>
    <a class="tarjeta-sol" href="<?= e($enlace) ?>" data-pilar="<?= e($s['pilar']) ?>">
      <span class="tarjeta-sol-img"><?= img_solucion($slug, $s['nombre'] . ': ' . $s['sub'], '(max-width: 575px) 86vw, (max-width: 991px) 46vw, 380px') ?></span>
      <span class="tarjeta-sol-cuerpo">
        <span class="tarjeta-sol-pilar"><?= icono($s['icono']) ?> <?= e($PILARES[$s['pilar']]['nombre']) ?></span>
        <strong><?= e($s['nombre']) ?></strong>
        <span class="tarjeta-sol-sub"><?= e($s['sub']) ?></span>
        <span class="tarjeta-sol-texto"><?= e($s['resumen']) ?></span>
        <span class="tarjeta-sol-mas">Conocer más <?= icono('arrow-right') ?></span>
      </span>
    </a>
    <?php return ob_get_clean();
}

/** Tarjeta de industria (icono + texto). */
function tarjeta_industria(array $i)
{
    ob_start(); ?>
    <div class="tarjeta-ind">
      <?php if (!empty($i['img'])): $base = '/img/industrias/' . e($i['img']); ?>
        <img class="tarjeta-ind-img" src="<?= $base ?>-400.webp" srcset="<?= $base ?>-400.webp 400w, <?= $base ?>.webp 800w"
          sizes="(max-width: 575px) 86vw, (max-width: 991px) 46vw, 300px" alt="" width="800" height="600" loading="lazy">
      <?php endif; ?>
      <div class="icono"><?= icono($i['icono']) ?></div>
      <h3><?= e($i['nombre']) ?></h3>
      <p><?= e($i['texto']) ?></p>
    </div>
    <?php return ob_get_clean();
}
