<?php
require __DIR__ . '/includes/bootstrap.php';
$pagina = 'inicio';
$titulo = 'CMC Service Network | Conectividad empresarial: fibra, microondas, LTE y satelital';
$precargar = con_version('/img/web/hero-cdmx.webp');
require ROOT . '/includes/header.php';
require ROOT . '/includes/carrusel.php';
?>

<header class="hero hero-red" style="--hero-img:url('<?= e($precargar) ?>')">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-7">
        <span class="etiqueta" style="color:var(--cmc-cian-brillante)">4 tecnologías · Red propia · 32 estados</span>
        <h1>Conectividad empresarial en todo México<br><span class="grad">que nunca se detiene</span></h1>
        <p class="lead">Fibra óptica y microondas en red propia, y LTE y satelital en los 32 estados para llegar a zonas sin red terrestre y respaldar tu enlace en automático. Sobre esa red integramos IoT, seguridad, cadena de suministro y TI.</p>
        <div class="d-flex flex-wrap gap-3 mt-4">
          <a href="<?= e(contacto_url('corenet', 'Quiero validar cobertura de enlace dedicado.')) ?>" class="btn btn-cmc" data-validar-cobertura>Cotizar enlace</a>
          <a href="/cobertura.php" class="btn btn-borde">Ver cobertura</a>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="enlaces-hero">
          <a href="#tecnologias" class="enlace-hero" data-ir-tecnologia="fibra">
            <span class="enlace-hero-icono"><?= icono('diagram-3') ?></span>
            <span><strong>Fibra óptica</strong>Red propia en CDMX, Edomex, Hidalgo, Puebla, Morelos y Tlaxcala, y cobertura extendida en 19 estados.</span>
          </a>
          <a href="#tecnologias" class="enlace-hero" data-ir-tecnologia="microondas">
            <span class="enlace-hero-icono"><?= icono('broadcast-pin') ?></span>
            <span><strong>Enlaces de microondas</strong>Enlaces dedicados de alta capacidad donde la fibra no llega o como segunda ruta.</span>
          </a>
          <a href="#tecnologias" class="enlace-hero" data-ir-tecnologia="lte">
            <span class="enlace-hero-icono"><?= icono('globe-americas') ?></span>
            <span><strong>LTE y satelital</strong>Cobertura en los 32 estados para zonas offnet y como respaldo automático.</span>
          </a>
          <div class="enlace-hero-pie"><?= icono('shield-check') ?> SLA 99.40% · NOC 7 x 24</div>
        </div>
      </div>
    </div>
  </div>
</header>

<?php require ROOT . '/includes/tecnologias.php'; ?>

<?php require ROOT . '/includes/cobertura-banner.php'; ?>

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

<?php $iot = $PILARES['iot']; ?>
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
        <?php // Imagen general de IoT; mientras no exista img/web/iot-plataforma.webp se usa la portada de IoT
        $img_iot = is_file(ROOT . '/img/web/iot-plataforma.webp') ? '/img/web/iot-plataforma.webp' : '/img/web/iot-hero.webp'; ?>
        <a href="<?= e($iot['url']) ?>" class="destacado-img d-block">
          <img src="<?= e($img_iot) ?>" alt="Plataforma IoT que conecta cámaras, sensores y dispositivos" width="1600" height="1200" loading="lazy">
        </a>
        <h3 class="h4 mt-4 mb-1">Una sola plataforma para todos tus dispositivos</h3>
        <p class="mb-0">Cámaras, sensores, lectores y etiquetas conectados a nuestra red, con una plataforma que analiza cada evento y te avisa en tiempo real.</p>
        <div class="chips"><span>Dispositivos conectados</span><span>Analítica con IA</span><span>Alertas en tiempo real</span><span>Monitoreo 7 x 24</span></div>
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
        <?= img_solucion('nosotros', 'Equipo de ingenieros de CMC en el centro de monitoreo', '(max-width: 991px) 92vw, 560px', 'class="bloque-img" loading="lazy"') ?>
      </div>
    </div>
  </div>
</section>

<?php require ROOT . '/includes/cta-contacto.php'; ?>

<?php require ROOT . '/includes/footer.php'; ?>
