<?php
// Pasaporte Networking: cuestionario corto del stand. Se guarda como prospecto (origen tarjeta, pagina "pasaporte").
require __DIR__ . '/../includes/bootstrap.php';
require ROOT . '/includes/datos.php';
require ROOT . '/includes/tarjeta.php';
require ROOT . '/includes/recursos.php';

$vslug = isset($_GET['v']) ? slugify($_GET['v']) : '';
$eslug = isset($_GET['e']) ? slugify($_GET['e']) : '';
$v = tarjeta_datos(tarjeta_vendedor($vslug), $CONTACTO);
$evento = tarjeta_evento($eslug);
$volver = '/tarjeta/' . (($qs = http_build_query(array_filter(['v' => $vslug, 'e' => $eslug]))) ? '?' . $qs : '');

header('Cache-Control: no-store');
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>Pasaporte Networking | CMC Service Network</title>
  <meta name="description" content="¿Cuál es tu reto? Te toma 2 minutos.">
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
    <a href="<?= e($volver) ?>"><img src="/imagenes/marca/logo-cmc-nav.png" alt="CMC Service Network" width="170" height="48"></a>
    <?php if ($evento): ?><span class="tarjeta-evento"><?= icono('geo-alt') ?> <?= e($evento['nombre']) ?></span><?php endif; ?>
  </header>

  <section class="tarjeta-form pasaporte">
    <h1>Pasaporte Networking</h1>
    <p>¿Cuál es tu reto? · Te toma 2 minutos</p>

    <form class="form-cmc js-form-prospecto" action="/api/contacto.php" method="post" novalidate>
      <input type="hidden" name="token" value="<?= e(form_token()) ?>">
      <input type="hidden" name="origen" value="tarjeta">
      <input type="hidden" name="pagina" value="pasaporte">
      <input type="hidden" name="vendedor" value="<?= $v['id'] ?>">
      <input type="hidden" name="evento" value="<?= $evento ? (int) $evento['id'] : 0 ?>">
      <div class="campo-trampa" aria-hidden="true"><label>Sitio web <input type="text" name="sitio_web" tabindex="-1" autocomplete="off"></label></div>

      <h2>Tus datos</h2>
      <div class="mb-3">
        <label class="form-label" for="p-nombre">Nombre *</label>
        <input class="form-control" id="p-nombre" name="nombre" required maxlength="120" autocomplete="name">
      </div>
      <div class="mb-3">
        <label class="form-label" for="p-empresa">Empresa</label>
        <input class="form-control" id="p-empresa" name="empresa" maxlength="150" autocomplete="organization">
      </div>
      <div class="mb-3">
        <label class="form-label" for="p-puesto">Puesto</label>
        <input class="form-control" id="p-puesto" name="puesto" maxlength="120" autocomplete="organization-title">
      </div>
      <div class="mb-3">
        <label class="form-label" for="p-tel">WhatsApp *</label>
        <input class="form-control" id="p-tel" name="telefono" type="tel" required maxlength="30" autocomplete="tel" inputmode="tel">
      </div>
      <div class="mb-3">
        <label class="form-label" for="p-email">Correo</label>
        <input class="form-control" id="p-email" name="email" type="email" maxlength="150" autocomplete="email" inputmode="email">
      </div>

      <h2>Tu reto</h2>
      <?php $n = 0; foreach ($PASAPORTE as $clave => $q): $n++; ?>
        <fieldset class="mb-3 pasaporte-pregunta"<?= $q['max'] > 1 ? ' data-max="' . $q['max'] . '"' : '' ?>>
          <legend><?= $n ?>. <?= e($q['pregunta']) ?><?php if ($q['nota']): ?> <small>(<?= e($q['nota']) ?>)</small><?php endif; ?></legend>
          <?php foreach ($q['opciones'] as $op): ?>
            <label class="pasaporte-opcion">
              <input type="<?= $q['multiple'] ? 'checkbox' : 'radio' ?>" name="<?= e($clave) ?><?= $q['multiple'] ? '[]' : '' ?>" value="<?= e($op) ?>">
              <span><?= e($op) ?></span>
            </label>
          <?php endforeach; ?>
          <?php if ($clave === 'tamano'): ?>
            <label class="form-label mt-2" for="p-sucursales">Número de sucursales</label>
            <input class="form-control" id="p-sucursales" name="sucursales" type="number" min="0" max="99999" inputmode="numeric">
          <?php endif; ?>
        </fieldset>
      <?php endforeach; ?>

      <div class="mb-3">
        <label class="form-label" for="p-mensaje">Comentarios</label>
        <textarea class="form-control" id="p-mensaje" name="mensaje" rows="2" maxlength="1000"></textarea>
      </div>

      <button class="btn btn-cmc w-100" type="submit"><span class="js-texto">Enviar mi pasaporte</span></button>
      <small class="d-block texto-suave mt-3 text-center">Al enviar aceptas nuestro <a href="<?= e('/' . legal_url($AVISO_PRIVACIDAD)) ?>" target="_blank">aviso de privacidad</a>.</small>
      <div class="form-estado mt-3" role="status" aria-live="polite"></div>
      <p class="pasaporte-sello" hidden>Muestra esta pantalla en el stand de CMC y recibe tu sello.</p>
      <a class="btn tarjeta-secundario w-100 mt-3 js-otro" href="" hidden>Llenar otro pasaporte</a>
    </form>
  </section>

  <footer class="tarjeta-pie">
    <a href="<?= e($volver) ?>">← Volver a la tarjeta</a> · <a href="/">cmcnetwork.mx</a>
  </footer>
</main>

<?= sprite_iconos() ?>
<script><?= js_en_linea('app.js') ?></script>
<script>
  // Maximo de opciones por pregunta (data-max)
  document.querySelectorAll('.pasaporte-pregunta[data-max]').forEach(function (fs) {
    var max = +fs.dataset.max;
    fs.addEventListener('change', function (ev) {
      if (ev.target.checked && fs.querySelectorAll('input:checked').length > max) ev.target.checked = false;
    });
  });
  // Tras enviar, ocultar el cuestionario y dejar el mensaje, el sello y "llenar otro"
  document.addEventListener('prospecto:enviado', function (ev) {
    var f = ev.target;
    f.querySelectorAll('h2, .mb-3, [type=submit], small').forEach(function (el) { el.hidden = true; });
    f.querySelectorAll('.pasaporte-sello, .js-otro').forEach(function (el) { el.hidden = false; });
    window.scrollTo(0, 0);
  }, true);
</script>
</body>
</html>
