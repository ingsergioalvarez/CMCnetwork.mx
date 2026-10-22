<?php
require __DIR__ . '/includes/bootstrap.php';
$pagina = 'inicio';
$titulo = 'CMC Service Network | Un solo socio tecnológico';
$precargar = '/img/web/hero-cdmx.webp';
require ROOT . '/includes/header.php';
require ROOT . '/includes/carrusel.php';

// Un mensaje general y uno por pilar (IoT primero)
$slides = [['Un solo', 'socio tecnológico', 'Conectamos tu operación con la tecnología que la hace crecer, protegerse y no detenerse nunca.']];
foreach ($PILARES as $p) {
    $slides[] = [$p['titulo'][0], $p['titulo'][1], $p['texto']];
}
?>

<header class="hero" style="--hero-img:url('/img/web/hero-cdmx.webp')">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-7">
        <div class="hero-slides">
          <?php foreach ($slides as $i => $s): ?>
            <div class="hero-slide<?= $i === 0 ? ' activo' : '' ?>">
              <<?= $i === 0 ? 'h1' : 'p class="h1"' ?>><?= e($s[0]) ?><br><span class="grad"><?= e($s[1]) ?></span></<?= $i === 0 ? 'h1' : 'p' ?>>
              <p class="lead"><?= e($s[2]) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="d-flex flex-wrap gap-3 mt-4">
          <a href="#contacto" class="btn btn-cmc">Solicita una cotización</a>
          <a href="#portafolio" class="btn btn-borde">Ver portafolio</a>
        </div>
        <div class="hero-puntos"></div>
      </div>
      <div class="col-lg-5 text-center d-none d-lg-block">
        <img src="/imagenes/marca/simbolo-cmc.webp" alt="" class="hero-simbolo" width="440" height="440" fetchpriority="low">
      </div>
    </div>
  </div>
</header>

<section class="fortalezas">
  <div class="container">
    <div class="row text-center g-4">
      <div class="col-6 col-md-3 contador"><strong data-hasta="5">0</strong><span>años operando</span></div>
      <div class="col-6 col-md-3 contador"><strong><span data-hasta="7">0</span> x <span data-hasta="24">0</span></strong><span>monitoreo NOC</span></div>
      <div class="col-6 col-md-3 contador"><strong data-hasta="<?= count($SOLUCIONES) ?>">0</strong><span>soluciones en un solo proveedor</span></div>
      <div class="col-6 col-md-3 contador"><strong data-hasta="6">0</strong><span>estados con presencia</span></div>
    </div>
    <div class="sellos">
      <span><?= icono('patch-check-fill') ?>Concesión oficial de telecomunicaciones</span>
      <span><?= icono('tools') ?>Centro de servicio certificado</span>
      <span><?= icono('geo-alt-fill') ?>Cobertura nacional</span>
      <span><?= icono('award-fill') ?>Clientes AAA</span>
    </div>
  </div>
</section>

<section id="portafolio" class="seccion seccion-clara">
  <div class="container">
    <div class="titulo-seccion text-center revelar">
      <span class="etiqueta">Portafolio</span>
      <h2>Todo lo que tu operación necesita, con un solo socio</h2>
      <p>IoT, automatización, cadena de suministro, conectividad y TI integrados por un mismo equipo, con un solo contrato y un solo soporte.</p>
    </div>
    <div class="filtros" data-filtros="#carrusel-portafolio" role="group" aria-label="Filtrar por área">
      <button type="button" class="activo" data-filtro="todos" aria-pressed="true">Todas</button>
      <?php foreach ($PILARES as $clave => $p): ?>
        <button type="button" data-filtro="<?= e($clave) ?>" aria-pressed="false"><?= icono($p['icono'], 'me-1') ?><?= e($p['nombre']) ?></button>
      <?php endforeach; ?>
    </div>
    <div id="carrusel-portafolio">
      <?php carrusel(array_map('tarjeta_solucion', array_keys($SOLUCIONES), $SOLUCIONES), ['auto' => true, 'etiqueta' => 'Portafolio de soluciones']); ?>
    </div>
  </div>
</section>

<?php $iot = $PILARES['iot']; $sd = $SOLUCIONES['scardetection']; ?>
<section class="seccion seccion-noche">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 revelar">
        <span class="etiqueta">Destacado · <?= e($iot['nombre']) ?></span>
        <h2 class="display-6 fw-bold"><?= e($iot['titulo'][0]) ?> <span class="grad-texto"><?= e($iot['titulo'][1]) ?></span></h2>
        <p class="lead"><?= e($iot['texto']) ?></p>
        <ul class="lista-check">
          <?php foreach (soluciones('iot') as $slug => $s): ?>
            <li><a href="<?= e(solucion_url($slug)) ?>"><strong><?= e($s['nombre']) ?></strong></a> · <?= e($s['sub']) ?></li>
          <?php endforeach; ?>
        </ul>
        <a href="<?= e($iot['url']) ?>" class="btn btn-cmc mt-2">Conoce IoT y Automatización</a>
      </div>
      <div class="col-lg-6 revelar">
        <a href="<?= e(solucion_url('scardetection')) ?>" class="destacado-img d-block">
          <span class="insignia-nuevo">NUEVO</span>
          <?= img_solucion('scardetection', $sd['nombre'], '(max-width: 991px) 92vw, 560px') ?>
        </a>
        <h3 class="h4 mt-4 mb-1"><?= e($sd['nombre']) ?>: <?= e(mb_strtolower($sd['sub'])) ?></h3>
        <p class="mb-0"><?= e($sd['resumen']) ?></p>
        <div class="chips"><?php foreach ($sd['destacado'] as $c): ?><span><?= e($c) ?></span><?php endforeach; ?></div>
      </div>
    </div>
  </div>
</section>

<section class="seccion">
  <div class="container">
    <div class="titulo-seccion text-center revelar">
      <span class="etiqueta">Áreas de solución</span>
      <h2>Cuatro pilares, un mismo equipo</h2>
    </div>
    <div class="row g-4">
      <?php foreach ($PILARES as $clave => $p): ?>
        <div class="col-sm-6 col-lg-3 revelar">
          <a class="pilar" href="<?= e($p['url']) ?>">
            <div class="icono"><?= icono($p['icono']) ?></div>
            <h3><?= e($p['nombre']) ?></h3>
            <p><?= e(implode(' · ', array_column(soluciones($clave), 'nombre'))) ?></p>
            <span class="tarjeta-sol-mas">Ver soluciones <?= icono('arrow-right') ?></span>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="seccion seccion-noche">
  <div class="container">
    <div class="titulo-seccion text-center revelar">
      <span class="etiqueta">Industrias</span>
      <h2>Soluciones para cada sector</h2>
    </div>
    <?php carrusel(array_map('tarjeta_industria', $INDUSTRIAS), ['por_vista' => 4, 'auto' => true, 'etiqueta' => 'Industrias que atendemos']); ?>
  </div>
</section>

<?php require ROOT . '/includes/certificaciones.php'; ?>

<section id="nosotros" class="seccion seccion-clara">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 revelar">
        <span class="etiqueta">Nosotros</span>
        <h2>Tu socio tecnológico desde hace 5 años</h2>
        <p>Diseñamos, integramos y operamos tecnología para empresas que no pueden detenerse. Atendemos a clientes AAA de retail, comercio electrónico, gobierno y municipios, con un mismo equipo responsable de la solución completa: desde el sensor y la cámara hasta la red, el software y el soporte.</p>
        <ul class="lista-check">
          <li>Un solo proveedor, un contrato y un soporte</li>
          <li>Monitoreo 7 x 24 desde nuestro NOC</li>
          <li>Ingenieros certificados y centro de servicio propio</li>
          <li>Cobertura nacional</li>
        </ul>
      </div>
      <div class="col-lg-6 revelar">
        <?= img_solucion('c2', 'Centro de monitoreo', '(max-width: 991px) 92vw, 560px', 'class="bloque-img" loading="lazy"') ?>
      </div>
    </div>
  </div>
</section>

<section class="seccion pt-0 seccion-clara">
  <div class="container">
    <div class="hermana revelar">
      <img src="/img/web/comunicalo.webp" alt="Comunícalo de México" width="420" height="138" loading="lazy">
      <div>
        <span class="etiqueta">Empresa hermana</span>
        <h2>¿Organizas un evento?</h2>
        <p>Internet y conectividad para eventos, convenciones y exposiciones con <?= e($COMUNICALO['nombre']) ?>.</p>
      </div>
      <a href="<?= e($COMUNICALO['url']) ?>" class="btn btn-cmc" target="_blank" rel="noopener">Visitar <?= e(parse_url($COMUNICALO['url'], PHP_URL_HOST)) ?> <?= icono('box-arrow-up-right', 'ms-1') ?></a>
    </div>
  </div>
</section>

<?php require ROOT . '/includes/form-contacto.php'; ?>

<div class="mapa mapa-fachada" data-mapa="<?= e($CONTACTO['mapa_embed']) ?>">
  <div>
    <?= icono('geo-alt-fill') ?>
    <p><?= e($CONTACTO['direccion']) ?></p>
    <div class="d-flex flex-wrap justify-content-center gap-3">
      <button type="button" class="btn btn-cmc">Ver mapa</button>
      <a href="<?= e($CONTACTO['mapa_link']) ?>" class="btn btn-borde" target="_blank" rel="noopener">Abrir en Google Maps</a>
    </div>
  </div>
</div>

<?php require ROOT . '/includes/footer.php'; ?>
