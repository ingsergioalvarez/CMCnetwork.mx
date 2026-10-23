<?php
require __DIR__ . '/includes/bootstrap.php';
$pagina = 'residencial';
$titulo = 'Internet residencial | CMC Service Network';
$descripcion = 'Planes de Internet y telefonía para tu hogar desde $600 al mes.';
$precargar = '/img/web/residencial.webp';
require ROOT . '/includes/header.php';
?>

<header class="hero hero-corto" style="--hero-img:url('/img/web/residencial.webp')">
  <div class="container">
    <span class="etiqueta" style="color:var(--cmc-cian-brillante)">Hogar</span>
    <h1>Internet <span class="grad">residencial</span></h1>
    <p class="lead">Experiencia de navegación rápida y sin interrupciones. Perfecto para entretenimiento, estudio y trabajo desde casa.</p>
    <a href="#planes" class="btn btn-cmc mt-3">Ver planes</a>
  </div>
</header>

<?php require ROOT . '/includes/planes.php'; ?>
<?php $interes = 'residencial'; require ROOT . '/includes/cta-contacto.php'; ?>
<?php require ROOT . '/includes/footer.php'; ?>
