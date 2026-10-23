<?php
// Plantilla de pagina de pilar. Variables: $pilar (clave en $PILARES), opcional $pasos (lista "como funciona").
defined('CMC') || exit;
require_once __DIR__ . '/datos.php';

$pil = $PILARES[$pilar];
$precargar = $pil['img'];
$pagina = $pilar;
$titulo = $pil['nombre'] . ' | CMC Service Network';
$descripcion = $pil['texto'];
require __DIR__ . '/header.php';
require __DIR__ . '/carrusel.php';

$lista = soluciones($pilar);
?>

<header class="hero hero-corto" style="--hero-img:url('<?= e($pil['img']) ?>')">
  <div class="container">
    <span class="etiqueta" style="color:var(--cmc-cian-brillante)"><?= icono($pil['icono'], 'me-1') ?><?= e($pil['nombre']) ?></span>
    <h1><?= e($pil['titulo'][0]) ?> <span class="grad"><?= e($pil['titulo'][1]) ?></span></h1>
    <p class="lead"><?= e($pil['texto']) ?></p>
    <div class="d-flex flex-wrap gap-3 mt-4">
      <a href="#contacto" class="btn btn-cmc">Solicita una cotización</a>
      <a href="#soluciones" class="btn btn-borde">Ver soluciones</a>
    </div>
  </div>
</header>

<?php if ($pilar === 'conectividad') { require __DIR__ . '/tecnologias.php'; require __DIR__ . '/cobertura.php'; } ?>

<section id="soluciones" class="seccion seccion-clara">
  <div class="container">
    <div class="titulo-seccion text-center revelar">
      <span class="etiqueta">Soluciones</span>
      <h2><?= count($lista) ?> soluciones para tu operación</h2>
    </div>
    <?php carrusel(array_map('tarjeta_solucion', array_keys($lista), $lista), ['por_vista' => count($lista) > 3 ? 3 : count($lista), 'etiqueta' => 'Soluciones de ' . $pil['nombre']]); ?>
  </div>
</section>

<?php if (!empty($pasos)): ?>
<section class="seccion">
  <div class="container">
    <div class="titulo-seccion text-center revelar">
      <span class="etiqueta">Cómo funciona</span>
      <h2>Del sensor a la decisión</h2>
    </div>
    <div class="row g-4">
      <?php foreach ($pasos as $i => $paso): ?>
        <div class="col-sm-6 col-lg-3 revelar">
          <div class="paso">
            <div class="paso-num"><?= icono($paso[0]) ?></div>
            <h3><?= ($i + 1) . '. ' . e($paso[1]) ?></h3>
            <p><?= e($paso[2]) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php $n = 0; foreach ($lista as $slug => $s): $n++;
    $noche = !empty($s['destacado']);
    $clase = $noche ? 'seccion-noche' : ($n % 2 ? '' : 'seccion-clara'); ?>
<section id="<?= e($slug) ?>" class="seccion solucion <?= $clase ?>">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 revelar<?= $n % 2 ? '' : ' order-lg-2' ?>">
        <div class="destacado-img">
          <?php if ($noche): ?><span class="insignia-nuevo">NUEVO</span><?php endif; ?>
          <?= img_solucion($slug, $s['nombre'] . ': ' . $s['sub'], '(max-width: 991px) 92vw, 560px') ?>
        </div>
      </div>
      <div class="col-lg-6 revelar">
        <span class="etiqueta"><?= icono($s['icono'], 'me-1') ?><?= e($s['sub']) ?></span>
        <h2><?= e($s['nombre']) ?></h2>
        <?php if ($noche): ?><div class="chips"><?php foreach ($s['destacado'] as $c): ?><span><?= e($c) ?></span><?php endforeach; ?></div><?php endif; ?>
        <div class="reto"><small>Reto del cliente</small><?= e($s['reto']) ?></div>
        <ul class="lista-check">
          <?php foreach ($s['incluye'] as $item): ?><li><?= e($item) ?></li><?php endforeach; ?>
        </ul>
        <a href="?interes=<?= e(rawurlencode($slug)) ?>#contacto" class="btn btn-cmc mt-2">Solicitar información</a>
      </div>
    </div>
  </div>
</section>
<?php endforeach; ?>

<?php if ($pilar === 'iot'): ?>
<section class="seccion seccion-noche">
  <div class="container">
    <div class="titulo-seccion text-center revelar">
      <span class="etiqueta">Industrias</span>
      <h2>Dónde aplicamos IoT y automatización</h2>
    </div>
    <?php carrusel(array_map('tarjeta_industria', $INDUSTRIAS), ['por_vista' => 4, 'auto' => true, 'etiqueta' => 'Industrias']); ?>
  </div>
</section>
<?php endif; ?>

<?php require __DIR__ . '/certificaciones.php'; ?>

<?php require __DIR__ . '/form-contacto.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>
