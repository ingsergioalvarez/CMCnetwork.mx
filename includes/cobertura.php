<?php
// Seccion de cobertura: cifras, filtros por tipo de red, mapa de Mexico y buscador por estado.
defined('CMC') || exit;
require_once __DIR__ . '/datos.php';

$mapa = require __DIR__ . '/mapa-mexico.php';
$cats = $COBERTURA['categorias'];
$conteo = array_count_values(array_column($COBERTURA['estados'], 1));
$total = count($COBERTURA['estados']);
$ordenados = $COBERTURA['estados'];
uasort($ordenados, function ($a, $b) { return strcoll($a[0], $b[0]); });
?>
<section id="cobertura" class="seccion seccion-noche cobertura">
  <div class="container">
    <div class="titulo-seccion text-center revelar">
      <span class="etiqueta">Cobertura</span>
      <h2>Red propia y cobertura en los 32 estados</h2>
      <p>Enlaces dedicados por fibra óptica y microondas en nuestra red, y conectividad en todo el país con aliados, LTE y satelital.</p>
    </div>

    <div class="row g-3 mb-4">
      <?php foreach ($COBERTURA['cifras'] as $c): ?>
        <div class="col-6 col-lg-3"><div class="cifra"><strong><?= e($c[0]) ?></strong><span><?= e($c[1]) ?></span></div></div>
      <?php endforeach; ?>
    </div>

    <div class="filtros filtros-cobertura" role="group" aria-label="Tipo de cobertura">
      <button type="button" class="activo" data-cobertura="todos" aria-pressed="true"><?= icono('layers') ?> Todo el país <b><?= $total ?></b></button>
      <?php foreach ($cats as $clave => $c): ?>
        <button type="button" data-cobertura="<?= e($clave) ?>" aria-pressed="false"><i class="punto" style="background:<?= e($c['color']) ?>"></i><?= e($c['nombre']) ?> <b><?= (int) $conteo[$clave] ?></b></button>
      <?php endforeach; ?>
      <button type="button" data-cobertura="lte" aria-pressed="false"><i class="punto" style="background:#38BDF8"></i>LTE y satelital <b><?= $total ?></b></button>
    </div>

    <div class="row g-4 align-items-stretch">
      <div class="col-lg-8">
        <div class="mapa-mx" data-filtro="todos">
          <svg viewBox="0 0 <?= (int) $mapa['w'] ?> <?= (int) $mapa['h'] ?>" role="img" aria-label="Mapa de cobertura de CMC en México">
            <?php foreach ($mapa['estados'] as $cod => $d): $info = $COBERTURA['estados'][$cod]; ?>
              <path d="<?= $d ?>" data-estado="<?= e($cod) ?>" data-cat="<?= e($info[1]) ?>" style="--c:<?= e($cats[$info[1]]['color']) ?>"><title><?= e($info[0]) ?></title></path>
            <?php endforeach; ?>
          </svg>
          <ul class="mapa-leyenda">
            <?php foreach ($cats as $c): ?><li><i class="punto" style="background:<?= e($c['color']) ?>"></i><?= e($c['nombre']) ?></li><?php endforeach; ?>
            <li><i class="punto" style="background:#38BDF8"></i>LTE y satelital</li>
          </ul>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="panel-cobertura">
          <label class="visually-hidden" for="cobertura-estado">Busca tu estado</label>
          <select class="form-select" id="cobertura-estado">
            <option value="">Busca tu estado…</option>
            <?php foreach ($ordenados as $cod => $info): ?><option value="<?= e($cod) ?>"><?= e($info[0]) ?></option><?php endforeach; ?>
          </select>
          <div class="panel-cobertura-cuerpo" aria-live="polite">
            <span class="insignia-cobertura" data-panel="insignia"><?= $total ?> / <?= $total ?> estados</span>
            <h3 data-panel="titulo">Cobertura en todo México</h3>
            <ul class="lista-cobertura" data-panel="lista">
              <?php foreach ($cats as $clave => $c): ?><li><i class="punto" style="background:<?= e($c['color']) ?>"></i><b><?= (int) $conteo[$clave] ?></b> <?= e($c['nombre']) ?></li><?php endforeach; ?>
              <li><i class="punto" style="background:#38BDF8"></i><b><?= $total ?></b> LTE y satelital</li>
            </ul>
            <p class="texto-suave small mb-0" data-panel="nota">Elige tu estado para ver con qué tecnología te conectamos.</p>
          </div>
          <a href="#contacto" class="btn btn-cmc w-100 mt-auto" data-validar-cobertura>Validar cobertura <?= icono('arrow-right') ?></a>
          <small class="d-block text-center texto-suave mt-2">*Todos nuestros servicios están sujetos a factibilidad técnica.</small>
        </div>
      </div>
    </div>
  </div>
  <script type="application/json" id="cobertura-datos"><?= json_encode(['categorias' => $cats, 'estados' => $COBERTURA['estados']], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?></script>
</section>
