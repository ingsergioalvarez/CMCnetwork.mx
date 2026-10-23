<?php
// Detalle de una solucion: solucion.php?s=slug
require __DIR__ . '/includes/bootstrap.php';
require_once ROOT . '/includes/datos.php';

$slug = isset($_GET['s']) && is_string($_GET['s']) ? $_GET['s'] : '';
if (!isset($SOLUCIONES[$slug])) {
    header('Location: /', true, 302);
    exit;
}

$s = $SOLUCIONES[$slug];
$pil = $PILARES[$s['pilar']];
$pagina = $s['pilar'];
$titulo = $s['nombre'] . ' · ' . $s['sub'] . ' | CMC Service Network';
$descripcion = $s['resumen'];
$interes = $slug;
require ROOT . '/includes/header.php';
require ROOT . '/includes/carrusel.php';

$otras = soluciones($s['pilar']);
unset($otras[$slug]);

// Proyectos llave en mano: el alcance sale del diagnostico, no de un paquete fijo
$pasos = [
    ['bi-search', 'Consultoría', 'Conocemos tu operación, visitamos el sitio y entendemos el problema a resolver.'],
    ['bi-diagram-3', 'Caso de negocio', 'Diseñamos la solución y definimos alcance, inversión y retorno esperado.'],
    ['bi-tools', 'Implementación', 'Suministro, instalación, configuración e integración llave en mano.'],
    ['bi-headset', 'Soporte', 'Acompañamiento, mantenimiento y evolución del proyecto.'],
];
?>

<header class="hero hero-corto">
  <div class="container">
    <a href="<?= e($pil['url']) ?>" class="etiqueta" style="color:var(--cmc-cian-brillante)"><?= icono($pil['icono'], 'me-1') ?><?= e($pil['nombre']) ?></a>
    <h1><?= e($s['nombre']) ?> <span class="grad"><?= e($s['sub']) ?></span></h1>
    <p class="lead"><?= e($s['resumen']) ?></p>
    <div class="d-flex flex-wrap gap-3 mt-4">
      <a href="<?= e(contacto_url($slug)) ?>" class="btn btn-cmc">Solicitar información</a>
      <a href="#alcance" class="btn btn-borde">Ver alcance</a>
    </div>
  </div>
</header>

<section class="seccion solucion">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 revelar">
        <div class="destacado-img">
          <?php if (!empty($s['destacado'])): ?><span class="insignia-nuevo">NUEVO</span><?php endif; ?>
          <?= img_solucion($slug, $s['nombre'] . ': ' . $s['sub'], '(max-width: 991px) 92vw, 560px', 'fetchpriority="high"') ?>
        </div>
      </div>
      <div class="col-lg-6 revelar">
        <span class="etiqueta"><?= icono($s['icono'], 'me-1') ?>Proyecto llave en mano</span>
        <h2>El reto que resolvemos</h2>
        <div class="reto"><small>Reto del cliente</small><?= e($s['reto']) ?></div>
        <?php if (!empty($s['destacado'])): ?><div class="chips"><?php foreach ($s['destacado'] as $c): ?><span><?= e($c) ?></span><?php endforeach; ?></div><?php endif; ?>
        <p>No vendemos un paquete cerrado: cada proyecto parte de una consultoría y un caso de negocio. Con eso definimos qué componentes necesitas, en qué orden implementarlos y cómo medir su resultado.</p>
      </div>
    </div>
  </div>
</section>

<section id="alcance" class="seccion seccion-noche solucion">
  <div class="container">
    <div class="titulo-seccion text-center revelar">
      <span class="etiqueta">Alcance</span>
      <h2>Lo que puede integrar tu proyecto</h2>
      <p>Estos son los componentes con los que trabajamos. El alcance final depende de tu operación y se define en conjunto.</p>
    </div>
    <div class="row g-4">
      <?php foreach ($s['alcance'] as $n => $c): ?>
        <div class="col-md-6 col-lg-4 revelar">
          <div class="componente">
            <span class="componente-num"><?= sprintf('%02d', $n + 1) ?></span>
            <h3><?= e($c[0]) ?></h3>
            <p><?= e($c[1]) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="seccion">
  <div class="container">
    <div class="titulo-seccion text-center revelar">
      <span class="etiqueta">Cómo trabajamos</span>
      <h2>De la consultoría a la operación</h2>
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
    <div class="text-center mt-5 revelar">
      <a href="<?= e(contacto_url($slug, 'Quiero agendar una consultoría de ' . $s['nombre'] . '.')) ?>" class="btn btn-cmc">Agenda una consultoría</a>
    </div>
  </div>
</section>

<?php if ($otras): ?>
<section class="seccion seccion-clara">
  <div class="container">
    <div class="titulo-seccion text-center revelar">
      <span class="etiqueta"><?= icono($pil['icono'], 'me-1') ?><?= e($pil['nombre']) ?></span>
      <h2>Más soluciones</h2>
    </div>
    <nav class="otras-sol" aria-label="Otras soluciones de <?= e($pil['nombre']) ?>">
      <?php foreach ($otras as $os => $o): ?>
        <a class="sol-mini revelar" href="<?= e(solucion_url($os)) ?>">
          <?= img_solucion($os, '', '(max-width: 575px) 92vw, 280px') ?>
          <span class="sol-mini-texto"><strong><?= e($o['nombre']) ?></strong><small><?= e($o['sub']) ?></small></span>
        </a>
      <?php endforeach; ?>
    </nav>
  </div>
</section>
<?php endif; ?>

<?php require ROOT . '/includes/cta-contacto.php'; ?>
<?php require ROOT . '/includes/footer.php'; ?>
