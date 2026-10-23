<?php
// Seccion de cobertura: cifras y mapa por capas (red propia, extendida, alianzas, LTE/satelital)
// con recorrido automatico, buscador por estado y panel. Logica en js/app.js.
defined('CMC') || exit;
require_once __DIR__ . '/datos.php';

$niveles = $COBERTURA['niveles'];
$lte = $COBERTURA['lte'];
$conteo = array_count_values(array_column($COBERTURA['estados'], 1));
$total = count($COBERTURA['estados']);
icono('play-fill'); // lo usa el boton de recorrido desde js/app.js

/** <img> de una capa del mapa con sus 5 tamanos. */
if (!function_exists('capa_mapa')) { function capa_mapa($archivo, $clase, $atributos = '')
{
    $b = '/img/cobertura/' . $archivo;
    $srcset = [];
    foreach ([480, 640, 800, 1200] as $w) $srcset[] = "$b-$w.webp {$w}w";
    $srcset[] = "$b.webp 1600w";
    return '<img src="' . $b . '-800.webp" srcset="' . implode(', ', $srcset) . '" sizes="(min-width: 1200px) 760px, 92vw"'
        . ' alt="" class="' . $clase . '" width="1600" height="1195" ' . $atributos . '>';
} }
?>
<section id="cobertura" class="seccion seccion-noche cobertura">
  <div class="container">
    <div class="titulo-seccion text-center revelar">
      <span class="etiqueta"><?= icono('activity') ?> Topología nacional multi-tecnología · 32 estados</span>
      <h2>Red propia, cobertura extendida y cobertura nacional</h2>
      <p>Fibra óptica y microondas en red propia en 6 estados del centro y en cobertura extendida en 19; alianzas donde no llegamos directo, y <strong>LTE y satelital en los 32 estados</strong> para zonas sin red terrestre y como respaldo automático.</p>
    </div>

    <div class="row g-3 mb-4">
      <?php foreach ($COBERTURA['cifras'] as $c): ?>
        <div class="col-6 col-lg-3"><div class="cifra"><strong><?= e($c[0]) ?></strong><span><?= e($c[1]) ?></span></div></div>
      <?php endforeach; ?>
    </div>

    <div class="cobmap" data-cobmap>
      <div class="cobmap-chips" role="group" aria-label="Tipo de cobertura">
        <button type="button" class="cobmap-chip activo" data-vista="todas"><?= icono('layers') ?> Todo el país <b><?= $total ?></b></button>
        <?php foreach ($niveles as $clave => $n): ?>
          <button type="button" class="cobmap-chip" data-vista="<?= e($clave) ?>" style="--chip:<?= e($n['color']) ?>"><i class="punto"></i><?= e($n['nombre']) ?> <b><?= (int) $conteo[$clave] ?></b></button>
        <?php endforeach; ?>
        <button type="button" class="cobmap-chip" data-vista="lte" style="--chip:<?= e($lte['color']) ?>"><?= icono('broadcast') ?> <?= e($lte['nombre']) ?> <b><?= $total ?></b></button>
        <button type="button" class="cobmap-chip cobmap-auto" data-recorrido aria-label="Pausar recorrido"><?= icono('pause-fill') ?></button>
      </div>

      <div class="cobmap-grid">
        <div class="cobmap-stage">
          <?= capa_mapa('base', 'cobmap-img', 'loading="lazy"') ?>
          <?php foreach ($niveles as $clave => $n): ?>
            <?= capa_mapa($n['img'], 'cobmap-img cobmap-capa activo', 'data-capa="' . e($clave) . '" loading="lazy"') ?>
          <?php endforeach; ?>
          <?= capa_mapa($lte['img'], 'cobmap-img cobmap-capa cobmap-capa-lte', 'data-capa="lte" loading="lazy"') ?>
          <ul class="cobmap-leyenda" aria-hidden="true">
            <?php foreach ($niveles as $n): ?><li><i class="punto" style="background:<?= e($n['color']) ?>"></i><?= e($n['nombre']) ?></li><?php endforeach; ?>
            <li><i class="punto" style="background:<?= e($lte['color']) ?>"></i><?= e($lte['nombre']) ?></li>
          </ul>
        </div>

        <aside class="cobmap-panel">
          <label class="cobmap-buscar">
            <?= icono('search') ?>
            <select data-buscar-estado aria-label="Busca tu estado">
              <option value="">Busca tu estado…</option>
              <?php foreach ($COBERTURA['estados'] as $i => $est): ?><option value="<?= $i ?>"><?= e($est[0]) ?></option><?php endforeach; ?>
            </select>
          </label>
          <div class="cobmap-info" data-panel aria-live="polite"></div>
          <a href="#contacto" class="btn btn-cmc w-100" data-validar-cobertura>Validar cobertura <?= icono('arrow-right') ?></a>
          <small class="d-block text-center texto-suave">*Todos nuestros servicios están sujetos a factibilidad técnica.</small>
        </aside>
      </div>

      <details class="cobmap-detalle">
        <summary>Ver la lista completa de estados por tipo de cobertura</summary>
        <?php foreach ($niveles as $clave => $n): ?>
          <p><strong><?= e($n['nombre']) ?>:</strong> <?= e(implode(', ', array_column(array_filter($COBERTURA['estados'], function ($x) use ($clave) { return $x[1] === $clave; }), 0))) ?>.</p>
        <?php endforeach; ?>
        <p><strong><?= e($lte['nombre']) ?>:</strong> los 32 estados.</p>
      </details>
    </div>
  </div>
  <script type="application/json" id="cobertura-datos"><?= json_encode(['niveles' => $niveles, 'lte' => $lte, 'estados' => $COBERTURA['estados']], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?></script>
</section>
