<?php
// Pantalla para el stand (tableta o monitor): marca, contacto del vendedor y el QR a /tarjeta/. Sin formulario.
require __DIR__ . '/../includes/bootstrap.php';
require ROOT . '/includes/datos.php';
require ROOT . '/includes/tarjeta.php';
require ROOT . '/includes/recursos.php';

$vslug = isset($_GET['v']) ? slugify($_GET['v']) : '';
$eslug = isset($_GET['e']) ? slugify($_GET['e']) : '';
$v = tarjeta_datos(tarjeta_vendedor($vslug), $CONTACTO);
$evento = tarjeta_evento($eslug);
$iniciales = mb_strtoupper(mb_substr($v['nombre'], 0, 1) . (strpos($v['nombre'], ' ') ? mb_substr(strrchr($v['nombre'], ' '), 1, 1) : ''));

// Celular a 10 digitos con espacios: 477 171 7277
$cel = substr(solo_digitos($v['telefono'] !== '' ? $v['telefono'] : $v['whatsapp']), -10);
$cel_texto = strlen($cel) === 10 ? substr($cel, 0, 3) . ' ' . substr($cel, 3, 3) . ' ' . substr($cel, 6) : $cel;

header('Cache-Control: no-store');
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title><?= e($v['nombre']) ?> | CMC Service Network</title>
  <meta name="robots" content="noindex">
  <meta name="theme-color" content="#06123D">
  <link rel="icon" href="/imagenes/marca/favicon.ico">
  <link rel="preload" href="/fonts/poppins-700.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="/fonts/poppins-400.woff2" as="font" type="font/woff2" crossorigin>
  <style><?= css_en_linea(['bootstrap-sitio.css', 'fuentes.css', 'marca.css', 'cmc.css', 'tarjeta.css']) ?></style>
</head>
<body class="tarjeta-body seccion-noche stand-body">
<main class="stand">

  <section class="stand-info">
    <img class="stand-logo" src="/imagenes/marca/logo-cmc-nav.png" alt="CMC Service Network" width="260" height="74">
    <p class="stand-lema">Un solo socio tecnológico</p>
    <?php if ($evento): ?><span class="tarjeta-evento"><?= icono('geo-alt') ?> <?= e($evento['nombre']) ?></span><?php endif; ?>

    <div class="stand-perfil">
      <div class="tarjeta-foto">
        <?php if ($v['foto']): ?>
          <img src="/tarjeta/foto.php?id=<?= $v['id'] ?>" alt="<?= e($v['nombre']) ?>" width="120" height="120">
        <?php else: ?>
          <span><?= e($iniciales) ?></span>
        <?php endif; ?>
      </div>
      <div>
        <h1><?= e($v['nombre']) ?></h1>
        <p class="tarjeta-puesto"><?= e($v['puesto']) ?></p>
      </div>
    </div>

    <ul class="stand-datos">
      <?php if ($cel_texto !== ''): ?><li><?= icono('whatsapp') ?> <?= e($cel_texto) ?></li><?php endif; ?>
      <?php if ($v['email'] !== ''): ?><li><?= icono('envelope-fill') ?> <?= e($v['email']) ?></li><?php endif; ?>
      <li><?= icono('globe2') ?> cmcnetwork.mx</li>
    </ul>
  </section>

  <section class="stand-qr">
    <div class="stand-qr-caja">
      <img src="<?= e(con_version('/imagenes/marca/qr-tarjeta.svg')) ?>" alt="QR a cmcnetwork.mx/tarjeta" width="410" height="410">
    </div>
    <p class="stand-qr-titulo">Escanéame</p>
    <p class="stand-qr-texto">Guarda mi contacto y llena tu <b>Pasaporte Networking</b></p>
  </section>

</main>
<?= sprite_iconos() ?>
</body>
</html>
