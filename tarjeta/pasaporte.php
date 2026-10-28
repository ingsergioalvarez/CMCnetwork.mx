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

      <div class="pasaporte-avance" aria-hidden="true">
        <span class="js-paso-texto">Tus datos</span>
        <div class="pasaporte-barra"><i class="js-barra"></i></div>
      </div>

      <!-- Un paso a la vez: tus datos y despues una pregunta por pantalla -->
      <div class="pasaporte-paso" data-paso="Tus datos">
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
      </div>

      <?php $n = 0; $total = count($PASAPORTE); foreach ($PASAPORTE as $clave => $q): $n++; ?>
        <fieldset class="pasaporte-paso pasaporte-pregunta" data-paso="Pregunta <?= $n ?> de <?= $total ?>"<?= $q['max'] > 1 ? ' data-max="' . $q['max'] . '"' : '' ?><?= $q['multiple'] ? '' : ' data-auto' ?> hidden>
          <legend><?= e($q['pregunta']) ?><?php if ($q['nota']): ?> <small>(<?= e($q['nota']) ?>)</small><?php endif; ?></legend>
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
          <?php if ($n === $total): ?>
            <label class="form-label mt-3" for="p-mensaje">Comentarios (opcional)</label>
            <textarea class="form-control" id="p-mensaje" name="mensaje" rows="2" maxlength="1000"></textarea>
          <?php endif; ?>
        </fieldset>
      <?php endforeach; ?>

      <div class="pasaporte-nav">
        <button class="btn tarjeta-secundario js-atras" type="button" hidden>Atrás</button>
        <button class="btn btn-cmc js-siguiente" type="button">Siguiente</button>
        <button class="btn btn-cmc js-enviar" type="submit" hidden><span class="js-texto">Enviar mi pasaporte</span></button>
      </div>
      <small class="pasaporte-aviso texto-suave mt-3 text-center js-aviso">Al enviar aceptas nuestro <a href="<?= e('/' . legal_url($AVISO_PRIVACIDAD)) ?>" target="_blank">aviso de privacidad</a>.</small>
      <div class="form-estado mt-3" role="status" aria-live="polite"></div>
      <div class="pasaporte-folio" hidden>
        <span>Tu folio</span>
        <strong class="js-folio"></strong>
        <p>Muestra este folio al ejecutivo de CMC en el stand y recibe tu sello.</p>
      </div>
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
  (function () {
    var form = document.querySelector('.pasaporte form');
    var pasos = [].slice.call(form.querySelectorAll('.pasaporte-paso'));
    var atras = form.querySelector('.js-atras'), siguiente = form.querySelector('.js-siguiente'), enviar = form.querySelector('.js-enviar');
    var estado = form.querySelector('.form-estado');
    var actual = 0;

    function mostrar(i) {
      actual = i;
      pasos.forEach(function (p, k) { p.hidden = k !== i; });
      form.querySelector('.js-paso-texto').textContent = pasos[i].dataset.paso;
      form.querySelector('.js-barra').style.width = ((i + 1) / pasos.length * 100) + '%';
      atras.hidden = i === 0;
      siguiente.hidden = i === pasos.length - 1;
      enviar.hidden = i !== pasos.length - 1;
      estado.className = 'form-estado mt-3';
      estado.textContent = '';
      window.scrollTo(0, 0);
    }

    // Antes de avanzar desde "Tus datos": nombre y WhatsApp obligatorios, correo valido si se escribe
    function datosValidos() {
      var ok = true;
      pasos[0].querySelectorAll('.campo-error').forEach(function (c) { c.remove(); });
      pasos[0].querySelectorAll('input').forEach(function (c) {
        c.classList.remove('is-invalid');
        var msg = '';
        if (c.required && !c.value.trim()) msg = 'Este campo es obligatorio.';
        else if (c.name === 'telefono' && c.value.replace(/\D/g, '').length < 8) msg = 'Escribe un teléfono válido (al menos 8 dígitos).';
        else if (c.type === 'email' && c.value && !c.checkValidity()) msg = 'El correo no es válido.';
        if (msg) {
          ok = false;
          c.classList.add('is-invalid');
          var d = document.createElement('div');
          d.className = 'campo-error';
          d.textContent = msg;
          c.insertAdjacentElement('afterend', d);
        }
      });
      return ok;
    }

    siguiente.addEventListener('click', function () {
      if (actual === 0 && !datosValidos()) return;
      mostrar(actual + 1);
    });
    atras.addEventListener('click', function () { mostrar(actual - 1); });
    // Enter en un campo de texto avanza en lugar de enviar
    form.addEventListener('keydown', function (ev) {
      if (ev.key === 'Enter' && ev.target.tagName === 'INPUT' && actual < pasos.length - 1) { ev.preventDefault(); siguiente.click(); }
    });

    pasos.forEach(function (fs, k) {
      var max = +fs.dataset.max;
      fs.addEventListener('change', function (ev) {
        // Maximo de opciones por pregunta (data-max)
        if (max && ev.target.checked && fs.querySelectorAll('input:checked').length > max) ev.target.checked = false;
        // Preguntas de una sola respuesta: avanzan solas al elegir (menos la ultima, que lleva el boton de enviar)
        if (fs.hasAttribute('data-auto') && ev.target.type === 'radio' && k < pasos.length - 1) {
          setTimeout(function () { if (actual === k) mostrar(k + 1); }, 250);
        }
      });
    });

    // Si el servidor marca un error en "Tus datos", regresar a ese paso sin perder el mensaje
    form.addEventListener('prospecto:error', function () {
      if (pasos[0].querySelector('.is-invalid')) {
        var clase = estado.className, texto = estado.textContent;
        mostrar(0);
        estado.className = clase;
        estado.textContent = texto;
      }
    });

    // Tras enviar: ocultar el cuestionario y mostrar el folio para el sello
    form.addEventListener('prospecto:enviado', function (ev) {
      pasos.concat([form.querySelector('.pasaporte-avance'), form.querySelector('.pasaporte-nav'), form.querySelector('.js-aviso')])
        .forEach(function (el) { el.hidden = true; });
      if (ev.detail && ev.detail.folio) {
        form.querySelector('.js-folio').textContent = ev.detail.folio;
        form.querySelector('.pasaporte-folio').hidden = false;
      }
      form.querySelector('.js-otro').hidden = false;
      window.scrollTo(0, 0);
    });

    mostrar(0);
  })();
</script>
</body>
</html>
