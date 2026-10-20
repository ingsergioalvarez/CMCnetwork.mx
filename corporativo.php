<?php
require __DIR__ . '/includes/bootstrap.php';
$pagina = 'corporativo';
$titulo = 'Internet corporativo y dedicado | CMC Service Network';
$descripcion = 'Internet dedicado empresarial, soluciones para WISP e Internet para eventos con cobertura nacional y monitoreo 7 x 24.';
require ROOT . '/includes/header.php';

$detalles = [
    ['portfolio-1.jpg', 'Internet 4G', 'Tecnología especialmente diseñada para brindar la mejor conexión a Internet.'],
    ['portfolio-2.jpg', 'Enrutamiento', 'Enrutamiento estático y dinámico, reenvío de puertos, reglas de tráfico y gestión VPN.'],
    ['portfolio-3.jpg', 'Monitoreo', 'Tecnología gestionable con monitoreo remoto desde la nube, para cientos de sitios.'],
    ['portfolio-4.jpg', 'Videovigilancia', 'Envía señales de video a través de una red móvil en lugar de una conexión dedicada.'],
    ['portfolio-5.jpg', 'Gestión remota', 'Gestión en la nube del servicio 4G con plataforma de alertas en tiempo real.'],
    ['portfolio-6.jpg', 'Redes multiservicio', 'Tecnología gestionable con monitoreo remoto desde la nube, para cientos de sitios.'],
];
?>

<header class="hero hero-corto" style="--hero-img:url('/img/web/portfolio-1.jpg')">
  <div class="container">
    <span class="etiqueta" style="color:var(--cmc-cian-brillante)">Empresas</span>
    <h1>Internet <span class="grad">dedicado</span></h1>
    <p class="lead">Conectividad de misión crítica para empresas y proveedores de Internet que no pueden detenerse.</p>
    <a href="#contacto" class="btn btn-cmc mt-3">Solicita una cotización</a>
  </div>
</header>

<section class="seccion">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 revelar">
        <span class="etiqueta">Dedicado empresarial</span>
        <h2>Para operaciones que no pueden detenerse</h2>
        <p>Desarrollado para grandes empresas y proveedores de servicios de Internet (WISP) con operaciones de misión crítica.</p>
        <ul class="lista-check">
          <li>Cobertura nacional</li>
          <li>Soluciones para WISP</li>
          <li>Velocidad personalizada</li>
          <li>Velocidades simétricas</li>
          <li>Estabilidad garantizada</li>
        </ul>
      </div>
      <div class="col-lg-6 revelar">
        <img src="/img/web/dedicado.jpg" alt="Internet dedicado empresarial" class="bloque-img" loading="lazy">
      </div>
    </div>
  </div>
</section>

<section id="eventos" class="seccion seccion-noche">
  <div class="container">
    <div class="row align-items-center g-5 flex-lg-row-reverse">
      <div class="col-lg-6 revelar">
        <span class="etiqueta">Internet para eventos</span>
        <h2>Tu evento, siempre conectado</h2>
        <p>Conectividad temporal para expos, congresos, conferencias y lanzamientos, instalada y monitoreada por nuestro equipo.</p>
        <ul class="lista-check">
          <li>Cobertura nacional</li>
          <li>Velocidades desde 10 Mbps hasta 100 Gbps</li>
        </ul>
        <a href="#contacto" class="btn btn-cmc mt-2">Cotiza tu evento</a>
      </div>
      <div class="col-lg-6 revelar">
        <img src="/img/web/eventos.jpg" alt="Internet para eventos" class="bloque-img" loading="lazy">
      </div>
    </div>
  </div>
</section>

<section class="seccion seccion-clara">
  <div class="container">
    <div class="titulo-seccion text-center revelar">
      <span class="etiqueta">Detalles</span>
      <h2>Tecnología gestionable</h2>
      <p>Servicio con monitoreo remoto desde la nube, para cientos de sitios.</p>
    </div>
    <div class="row g-4">
      <?php foreach ($detalles as $d): ?>
        <div class="col-sm-6 col-lg-4 revelar">
          <div class="detalle">
            <img src="/img/web/<?= e($d[0]) ?>" alt="<?= e($d[1]) ?>" loading="lazy">
            <div class="detalle-texto"><h3><?= e($d[1]) ?></h3><p><?= e($d[2]) ?></p></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require ROOT . '/includes/form-contacto.php'; ?>
<?php require ROOT . '/includes/footer.php'; ?>
