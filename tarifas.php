<?php
require __DIR__ . '/includes/bootstrap.php';
$pagina = 'tarifas';
$titulo = 'Planes, paquetes y tarifas | CMC Service Network';
$descripcion = 'Consulta los planes CMC 20, CMC 40 y CMC 90 de Internet y telefonía residencial.';
require ROOT . '/includes/header.php';
?>

<header class="hero hero-corto" style="--hero-img:url('/img/slider/bg3.jpg')">
  <div class="container">
    <span class="etiqueta" style="color:var(--cmc-cian-brillante)">Tarifas</span>
    <h1>Planes y <span class="grad">paquetes</span></h1>
    <p class="lead">Elige el plan que mejor se adapta a tu hogar. Descarga el formato simplificado de cada paquete.</p>
  </div>
</header>

<?php require ROOT . '/includes/planes.php'; ?>
<?php require ROOT . '/includes/form-contacto.php'; ?>
<?php require ROOT . '/includes/footer.php'; ?>
