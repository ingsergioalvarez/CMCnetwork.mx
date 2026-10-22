<?php
require __DIR__ . '/includes/bootstrap.php';
$pagina = 'inicio';
$titulo = 'CMC Service Network | Conectividad empresarial y residencial';
require ROOT . '/includes/header.php';

$slides = [
    ['Conectividad empresarial', 'sin fronteras', 'Soluciones de conectividad de clase empresarial para eventos y operaciones críticas, con soporte 7 x 24.'],
    ['Internet dedicado', 'de alto desempeño', 'Velocidades simétricas y una fiabilidad incomparable para mantener tus operaciones en movimiento sin interrupciones.'],
    ['Respaldo de alta velocidad', 'con 4G y 5G', 'Mantén tu empresa conectada en todo momento y garantiza la continuidad del negocio en cualquier situación.'],
    ['Conexión global', 'Internet satelital', 'Cobertura y ancho de banda garantizado para empresas que operan en ubicaciones remotas o desafiantes.'],
];
?>

<header class="hero" style="--hero-img:url('/img/slider/bg2.jpg')">
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
          <a href="#servicios" class="btn btn-borde">Ver servicios</a>
        </div>
        <div class="hero-puntos"></div>
      </div>
      <div class="col-lg-5 text-center d-none d-lg-block">
        <img src="/imagenes/marca/simbolo-cmc.png?v=2" alt="" class="hero-simbolo" width="440" height="440">
      </div>
    </div>
  </div>
</header>

<section id="servicios" class="seccion seccion-clara">
  <div class="container">
    <div class="titulo-seccion text-center revelar">
      <span class="etiqueta">Nuestros servicios</span>
      <h2>Conectividad para cada operación</h2>
      <p>Desde conferencias ejecutivas hasta lanzamientos de alto nivel, confía en nosotros para una experiencia sin interrupciones en cada conexión.</p>
    </div>
    <div class="row g-4">
      <?php foreach ($SERVICIOS as $s): ?>
        <div class="col-md-6 col-lg-4 revelar">
          <div class="tarjeta-servicio">
            <div class="icono"><i class="bi <?= e($s['icono']) ?>"></i></div>
            <h3><?= e($s['titulo']) ?></h3>
            <p><?= e($s['texto']) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section id="productos" class="seccion">
  <div class="container">
    <div class="titulo-seccion text-center revelar">
      <span class="etiqueta">Productos</span>
      <h2>Elige la conexión ideal</h2>
    </div>
    <div class="row g-4">
      <div class="col-lg-6 revelar">
        <a href="/corporativo.php" class="producto text-decoration-none">
          <img src="/img/web/corporativo.jpg" alt="Internet corporativo" loading="lazy">
          <div class="producto-texto">
            <span class="etiqueta" style="color:var(--cmc-cian-brillante)">Empresas</span>
            <h3>Internet corporativo</h3>
            <p>Alta velocidad, conexión segura y soporte 24/7. Mantén a tu equipo siempre conectado.</p>
            <span class="btn btn-cmc btn-sm">Más detalles</span>
          </div>
        </a>
      </div>
      <div class="col-lg-6 revelar">
        <a href="/residencial.php" class="producto text-decoration-none">
          <img src="/img/web/residencial.jpg" alt="Internet residencial" loading="lazy">
          <div class="producto-texto">
            <span class="etiqueta" style="color:var(--cmc-cian-brillante)">Hogar</span>
            <h3>Internet residencial</h3>
            <p>Navegación rápida y sin interrupciones para entretenimiento, estudio y trabajo desde casa.</p>
            <span class="btn btn-cmc btn-sm">Ver planes</span>
          </div>
        </a>
      </div>
    </div>
  </div>
</section>

<section class="contadores">
  <div class="container">
    <div class="row text-center g-4">
      <div class="col-md-4 contador"><strong data-hasta="365">0</strong><span>días de soporte al año</span></div>
      <div class="col-md-4 contador"><strong data-hasta="24">0</strong><span>horas de monitoreo NOC</span></div>
      <div class="col-md-4 contador"><strong data-hasta="100">0</strong><span>Gbps de capacidad máxima</span></div>
    </div>
  </div>
</section>

<section id="nosotros" class="seccion seccion-clara">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 revelar">
        <span class="etiqueta">Nosotros</span>
        <h2>Expertos en diseño, integración y operación tecnológica</h2>
        <p>Somos un equipo especializado en conectividad de misión crítica. Diseñamos, integramos y operamos soluciones de Internet dedicado, respaldo móvil y satelital para empresas, eventos y hogares en todo México.</p>
        <ul class="lista-check">
          <li>Cobertura nacional</li>
          <li>Monitoreo remoto desde la nube</li>
          <li>Velocidades desde 10 Mbps hasta 100 Gbps</li>
          <li>Atención personalizada</li>
        </ul>
      </div>
      <div class="col-lg-6 revelar">
        <img src="/img/web/nosotros.jpg" alt="Equipo CMC" class="bloque-img" loading="lazy">
      </div>
    </div>
  </div>
</section>

<?php require ROOT . '/includes/form-contacto.php'; ?>

<div class="mapa"><iframe src="<?= e($CONTACTO['mapa_embed']) ?>" loading="lazy" title="Ubicación de CMC" referrerpolicy="no-referrer-when-downgrade"></iframe></div>

<?php require ROOT . '/includes/footer.php'; ?>
