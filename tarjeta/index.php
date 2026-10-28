<?php
// Tarjeta digital para el QR de eventos.
require __DIR__ . '/../includes/bootstrap.php';
require ROOT . '/includes/datos.php';
require ROOT . '/includes/tarjeta.php';
require ROOT . '/includes/recursos.php';

$vslug = isset($_GET['v']) ? slugify($_GET['v']) : '';
$eslug = isset($_GET['e']) ? slugify($_GET['e']) : '';
$v = tarjeta_datos(tarjeta_vendedor($vslug), $CONTACTO);
$evento = tarjeta_evento($eslug);

$qs = http_build_query(array_filter(['v' => $v['slug']]));
$vcard = '/tarjeta/vcard.php' . ($qs ? '?' . $qs : '');
$pasaporte = '/tarjeta/pasaporte.php' . (($pqs = http_build_query(array_filter(['v' => $vslug, 'e' => $eslug]))) ? '?' . $pqs : '');
$wa = 'https://wa.me/' . wa_numero($v['whatsapp']) . '?text=' . rawurlencode('Hola, los conocí' . ($evento ? ' en ' . $evento['nombre'] : '') . '. Estoy buscando información de CMC Service Network.');

header('Cache-Control: no-store'); // el vendedor activo cambia desde el panel
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title><?= e($v['nombre']) ?></title>
  <meta name="description" content="Guarda nuestro contacto y déjanos tus datos.">
  <meta name="robots" content="noindex">
  <meta name="theme-color" content="#06123D">
  <link rel="icon" href="/imagenes/marca/favicon.ico">
  <link rel="apple-touch-icon" href="/imagenes/marca/apple-touch-icon.png">
  <link rel="preload" href="/fonts/poppins-700.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="/fonts/poppins-400.woff2" as="font" type="font/woff2" crossorigin>
  <style><?= css_en_linea(['bootstrap-sitio.css', 'fuentes.css', 'marca.css', 'cmc.css', 'tarjeta.css']) ?></style>
</head>
<body class="tarjeta-body seccion-noche">
<main class="tarjeta-wrap">

  <header class="tarjeta-top">
    <img src="/imagenes/marca/logo-cmc-nav.png" alt="CMC Service Network" width="170" height="48">
    <?php if ($evento): ?><span class="tarjeta-evento"><?= icono('geo-alt') ?> <?= e($evento['nombre']) ?></span><?php endif; ?>
  </header>

  <section class="tarjeta-perfil">
    <div class="tarjeta-foto">
      <?php if ($v['foto']): ?>
        <img src="/tarjeta/foto.php?id=<?= $v['id'] ?>" alt="<?= e($v['nombre']) ?>" width="120" height="120">
      <?php else: ?>
        <img src="/imagenes/marca/simbolo-cmc.png" alt="" width="120" height="120" class="tarjeta-simbolo">
      <?php endif; ?>
    </div>
    <h1><?= e($v['nombre']) ?></h1>
    <p class="tarjeta-puesto"><?= e($v['puesto']) ?></p>

    <a href="<?= e($vcard) ?>" class="btn btn-cmc w-100 tarjeta-guardar"><?= icono('person-plus-fill', 'me-2') ?>Guardar contacto</a>
    <a href="<?= e($pasaporte) ?>" class="btn tarjeta-secundario w-100 mt-3"><?= icono('check2', 'me-2') ?>Llenar Pasaporte Networking</a>

    <div class="tarjeta-acciones">
      <?php if ($v['telefono']): ?><a href="tel:<?= e(solo_digitos($v['telefono'])) ?>"><?= icono('telephone-fill') ?><span>Llamar</span></a><?php endif; ?>
      <a href="<?= e($wa) ?>" target="_blank" rel="noopener"><?= icono('whatsapp') ?><span>WhatsApp</span></a>
      <?php if ($v['email']): ?><a href="mailto:<?= e($v['email']) ?>"><?= icono('envelope-fill') ?><span>Correo</span></a><?php endif; ?>
      <a href="/" target="_blank"><?= icono('globe2') ?><span>Sitio web</span></a>
    </div>
  </section>

  <section class="tarjeta-form">
    <h2>¿Te contactamos?</h2>
    <p>Déjanos tus datos y un asesor te enviará información a tu medida.</p>

    <form class="form-cmc js-form-prospecto" action="/api/contacto.php" method="post" novalidate>
      <input type="hidden" name="token" value="<?= e(form_token()) ?>">
      <input type="hidden" name="origen" value="tarjeta">
      <input type="hidden" name="pagina" value="tarjeta">
      <input type="hidden" name="vendedor" value="<?= $v['id'] ?>">
      <input type="hidden" name="evento" value="<?= $evento ? (int) $evento['id'] : 0 ?>">
      <div class="campo-trampa" aria-hidden="true"><label>Sitio web <input type="text" name="sitio_web" tabindex="-1" autocomplete="off"></label></div>

      <div class="mb-3">
        <label class="form-label" for="t-nombre">Nombre *</label>
        <input class="form-control" id="t-nombre" name="nombre" required maxlength="120" autocomplete="name">
      </div>
      <div class="mb-3">
        <label class="form-label" for="t-empresa">Empresa</label>
        <input class="form-control" id="t-empresa" name="empresa" maxlength="150" autocomplete="organization">
      </div>
      <div class="mb-3">
        <label class="form-label" for="t-tel">Teléfono / WhatsApp *</label>
        <input class="form-control" id="t-tel" name="telefono" type="tel" required maxlength="30" autocomplete="tel" inputmode="tel">
      </div>
      <div class="mb-3">
        <label class="form-label" for="t-email">Correo electrónico</label>
        <input class="form-control" id="t-email" name="email" type="email" maxlength="150" autocomplete="email" inputmode="email">
      </div>
      <div class="mb-3">
        <label class="form-label" for="t-interes">Me interesa</label>
        <select class="form-select" id="t-interes" name="interes">
          <option value="">Selecciona una opción</option>
          <?php foreach ($INTERESES as $valor => $texto): ?>
            <option value="<?= e($valor) ?>"><?= e($texto) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label" for="t-mensaje">Comentarios</label>
        <textarea class="form-control" id="t-mensaje" name="mensaje" rows="3" maxlength="2000"></textarea>
      </div>
      <button class="btn btn-cmc w-100" type="submit"><span class="js-texto">Enviar mis datos</span></button>
      <small class="d-block texto-suave mt-3 text-center">Al enviar aceptas nuestro <a href="<?= e('/' . legal_url($AVISO_PRIVACIDAD)) ?>" target="_blank">aviso de privacidad</a>.</small>
      <div class="form-estado mt-3" role="status" aria-live="polite"></div>
    </form>
  </section>

  <footer class="tarjeta-pie">
    <a href="/">cmcnetwork.mx</a> · <?php foreach ($CONTACTO['telefonos'] as $i => $t): ?><?= $i ? ' · ' : '' ?><a href="tel:<?= e($t['tel']) ?>"><?= e($t['texto']) ?></a><?php endforeach; ?>
  </footer>
</main>

<?= sprite_iconos() ?>
<script><?= js_en_linea('app.js') ?></script>
<script>
  // Tras enviar, ocultar el formulario y dejar el mensaje de gracias
  document.addEventListener('prospecto:enviado', function (ev) {
    var f = ev.target;
    f.querySelectorAll('.mb-3, [type=submit], small').forEach(function (el) { el.hidden = true; });
  }, true);
</script>
</body>
</html>
