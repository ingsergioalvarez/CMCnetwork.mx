<?php
// Recuadro de cobertura que lleva a /cobertura.php (el mapa completo vive ahi).
defined('CMC') || exit;
require_once __DIR__ . '/datos.php';

$propios = array_column(array_filter($COBERTURA['estados'], function ($x) { return $x[1] === 'propia'; }), 0);
$ultimo = array_pop($propios);
?>
<section class="seccion">
  <div class="container">
    <div class="cob-banner revelar">
      <div class="cob-banner-mapa" aria-hidden="true">
        <img src="/img/cobertura/base-800.webp" alt="" width="1600" height="1195" loading="lazy">
        <?php foreach ($COBERTURA['niveles'] as $n): // mismas capas que la vista "Todo el pais" del mapa ?>
          <img src="/img/cobertura/<?= e($n['img']) ?>-800.webp" alt="" width="1600" height="1195" loading="lazy">
        <?php endforeach; ?>
      </div>
      <div class="cob-banner-cuerpo">
        <span class="cob-banner-etiqueta"><?= icono('geo-alt') ?> Cobertura de conectividad</span>
        <h2>¿Ya llegamos a tu ubicación?</h2>
        <p>Red propia de fibra óptica y microondas en
          <?php foreach ($propios as $i => $est): ?><?= $i ? ', ' : '' ?><strong><?= e($est) ?></strong><?php endforeach; ?> y <strong><?= e($ultimo) ?></strong>.
          En el resto del país llegamos con cobertura extendida y alianzas, y con LTE y satelital en los 32 estados.</p>
        <form class="cob-banner-buscar" action="/cobertura.php" method="get">
          <?= icono('geo-alt') ?>
          <select name="estado" aria-label="Elige tu estado" required>
            <option value="">Elige tu estado…</option>
            <?php foreach ($COBERTURA['estados'] as $i => $est): ?><option value="<?= $i ?>"><?= e($est[0]) ?></option><?php endforeach; ?>
          </select>
          <button type="submit" class="btn btn-cmc btn-sm">Consultar <?= icono('arrow-right') ?></button>
        </form>
        <ul class="cob-banner-cifras">
          <li><b><?= count($propios) + 1 ?> estados</b> con red propia</li>
          <li><b>32 / 32</b> estados con cobertura nacional</li>
          <li><b>99.95%</b> de disponibilidad</li>
        </ul>
        <p class="cob-banner-nota"><?= icono('cpu') ?><span><strong>Esta cobertura aplica a enlaces de conectividad.</strong> Los proyectos de IoT, seguridad, cadena de suministro y TI los implementamos en cualquier parte de México.</span></p>
      </div>
    </div>
  </div>
</section>
