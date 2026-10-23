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

<script>
// Enlaces antiguos (pagina#solucion) llevan al detalle de la solucion
(function () {
  var s = location.hash.slice(1), lista = <?= json_encode(array_keys($lista)) ?>;
  if (lista.indexOf(s) >= 0) location.replace('/solucion.php?s=' + s);
})();
</script>

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
