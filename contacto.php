<?php
// Pagina de contacto: formulario, datos, direccion y mapa.
// Parametros opcionales: ?interes=slug (opcion preseleccionada), ?mensaje= (texto inicial), ?de= (pagina de origen).
require __DIR__ . '/includes/bootstrap.php';
require_once ROOT . '/includes/datos.php';

$param = function ($nombre) { return isset($_GET[$nombre]) && is_string($_GET[$nombre]) ? $_GET[$nombre] : ''; };
$interes_sel = $param('interes');
$mensaje = mb_substr($param('mensaje'), 0, 300);
$origen = preg_match('/^[a-z0-9-]{1,40}$/', $param('de')) ? $param('de') : 'contacto';

$pagina = 'contacto';
$titulo = 'Contacto | CMC Service Network';
$descripcion = 'Escríbenos o llámanos: un asesor de CMC Service Network te ayudará a definir tu proyecto. ' . $CONTACTO['direccion'];
require ROOT . '/includes/header.php';
$wa = 'https://wa.me/' . $CONTACTO['whatsapp'] . '?text=' . rawurlencode($CONTACTO['whatsapp_msg']);
?>

<section id="contacto" class="seccion seccion-noche contacto-pagina">
  <div class="container">
    <div class="titulo-seccion text-center">
      <span class="etiqueta" style="color:var(--cmc-cian-brillante)">Contacto</span>
      <h1>Hablemos de tu <span class="grad-texto">proyecto</span></h1>
      <p>Déjanos tus datos y uno de nuestros asesores te contactará para entender tu operación y proponerte una solución a tu medida.</p>
    </div>

    <div class="row g-4 align-items-start">
      <div class="col-lg-7">
        <form class="form-cmc tarjeta-vidrio js-form-prospecto" action="/api/contacto.php" method="post" novalidate>
          <input type="hidden" name="token" value="<?= e(form_token()) ?>">
          <input type="hidden" name="origen" value="web">
          <input type="hidden" name="pagina" value="<?= e($origen) ?>">
          <div class="campo-trampa" aria-hidden="true"><label>Sitio web <input type="text" name="sitio_web" tabindex="-1" autocomplete="off"></label></div>

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label" for="c-nombre">Nombre *</label>
              <input class="form-control" id="c-nombre" name="nombre" required maxlength="120" autocomplete="name">
            </div>
            <div class="col-md-6">
              <label class="form-label" for="c-empresa">Empresa</label>
              <input class="form-control" id="c-empresa" name="empresa" maxlength="150" autocomplete="organization">
            </div>
            <div class="col-md-6">
              <label class="form-label" for="c-email">Correo electrónico *</label>
              <input class="form-control" id="c-email" name="email" type="email" required maxlength="150" autocomplete="email">
            </div>
            <div class="col-md-6">
              <label class="form-label" for="c-tel">Teléfono *</label>
              <input class="form-control" id="c-tel" name="telefono" type="tel" required maxlength="30" autocomplete="tel">
            </div>
            <div class="col-12">
              <label class="form-label" for="c-interes">Me interesa</label>
              <select class="form-select" id="c-interes" name="interes">
                <option value="">Selecciona una opción</option>
                <?php foreach ($INTERESES as $valor => $texto): ?>
                  <option value="<?= e($valor) ?>"<?= $interes_sel === $valor ? ' selected' : '' ?>><?= e($texto) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label" for="c-mensaje">Mensaje</label>
              <textarea class="form-control" id="c-mensaje" name="mensaje" rows="4" maxlength="2000"><?= e($mensaje) ?></textarea>
            </div>
            <div class="col-12 d-flex flex-wrap align-items-center justify-content-between gap-3">
              <small class="texto-suave">Al enviar aceptas nuestro <a href="<?= e('/' . legal_url($AVISO_PRIVACIDAD)) ?>" target="_blank">aviso de privacidad</a>.</small>
              <button class="btn btn-cmc" type="submit"><span class="js-texto">Enviar mensaje</span></button>
            </div>
            <div class="col-12"><div class="form-estado" role="status" aria-live="polite"></div></div>
          </div>
        </form>
      </div>

      <div class="col-lg-5">
        <div class="datos-contacto">
          <div class="dato">
            <?= icono('telephone') ?>
            <div>
              <h3>Teléfonos</h3>
              <?php foreach ($CONTACTO['telefonos'] as $t): ?>
                <a href="tel:<?= e($t['tel']) ?>"><?= e($t['texto']) ?></a><br>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="dato">
            <?= icono('whatsapp') ?>
            <div><h3>WhatsApp</h3><a href="<?= e($wa) ?>" target="_blank" rel="noopener">Escríbenos</a></div>
          </div>
          <div class="dato">
            <?= icono('clock') ?>
            <div>
              <h3>Horario de atención</h3>
              <?php foreach ($CONTACTO['horario'] as $h): ?><?= e($h) ?><br><?php endforeach; ?>
            </div>
          </div>
          <?php foreach ($CONTACTO['emails'] as $m): ?>
            <div class="dato">
              <?= icono('envelope') ?>
              <div><h3><?= e($m['titulo']) ?></h3><a href="mailto:<?= e($m['email']) ?>"><?= e($m['email']) ?></a></div>
            </div>
          <?php endforeach; ?>
          <div class="dato">
            <?= icono('geo-alt') ?>
            <div><h3>Oficinas</h3><?= e($CONTACTO['direccion']) ?><br><a href="<?= e($CONTACTO['mapa_link']) ?>" target="_blank" rel="noopener">Cómo llegar <?= icono('box-arrow-up-right') ?></a></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="mapa">
  <iframe src="<?= e($CONTACTO['mapa_embed']) ?>" title="Ubicación de las oficinas de CMC" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
</div>

<?php require ROOT . '/includes/footer.php'; ?>
