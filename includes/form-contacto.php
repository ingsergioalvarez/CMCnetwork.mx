<?php
// Seccion de contacto con formulario. Variable opcional: $pagina (se guarda como origen).
defined('CMC') || exit;
require_once __DIR__ . '/datos.php';
?>
<section id="contacto" class="seccion seccion-noche">
  <div class="container">
    <div class="titulo-seccion text-center revelar">
      <span class="etiqueta">Contacto</span>
      <h2>Hablemos de tu conectividad</h2>
      <p>Déjanos tus datos y uno de nuestros asesores te contactará con una solución a tu medida.</p>
    </div>

    <div class="row g-4 align-items-start">
      <div class="col-lg-7 revelar">
        <form class="form-cmc tarjeta-vidrio js-form-prospecto" action="/api/contacto.php" method="post" novalidate>
          <input type="hidden" name="token" value="<?= e(form_token()) ?>">
          <input type="hidden" name="origen" value="web">
          <input type="hidden" name="pagina" value="<?= e(isset($pagina) ? $pagina : '') ?>">
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
                <?php foreach ($INTERESES as $valor => $texto): ?>
                  <option value="<?= e($valor) ?>"<?= (isset($pagina) && $pagina === $valor) ? ' selected' : '' ?>><?= e($texto) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label" for="c-mensaje">Mensaje</label>
              <textarea class="form-control" id="c-mensaje" name="mensaje" rows="4" maxlength="2000"></textarea>
            </div>
            <div class="col-12 d-flex flex-wrap align-items-center justify-content-between gap-3">
              <small class="texto-suave">Al enviar aceptas nuestro <a href="<?= e('/' . legal_url($AVISO_PRIVACIDAD)) ?>" target="_blank">aviso de privacidad</a>.</small>
              <button class="btn btn-cmc" type="submit"><span class="js-texto">Enviar mensaje</span></button>
            </div>
            <div class="col-12"><div class="form-estado" role="status" aria-live="polite"></div></div>
          </div>
        </form>
      </div>

      <div class="col-lg-5 revelar">
        <div class="datos-contacto">
          <div class="dato">
            <i class="bi bi-telephone"></i>
            <div>
              <h3>Teléfonos</h3>
              <?php foreach ($CONTACTO['telefonos'] as $t): ?>
                <a href="tel:<?= e($t['tel']) ?>"><?= e($t['texto']) ?></a><br>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="dato">
            <i class="bi bi-clock"></i>
            <div>
              <h3>Horario de atención</h3>
              <?php foreach ($CONTACTO['horario'] as $h): ?><?= e($h) ?><br><?php endforeach; ?>
            </div>
          </div>
          <?php foreach ($CONTACTO['emails'] as $m): ?>
            <div class="dato">
              <i class="bi bi-envelope"></i>
              <div><h3><?= e($m['titulo']) ?></h3><a href="mailto:<?= e($m['email']) ?>"><?= e($m['email']) ?></a></div>
            </div>
          <?php endforeach; ?>
          <div class="dato">
            <i class="bi bi-geo-alt"></i>
            <div><h3>Oficinas</h3><a href="<?= e($CONTACTO['mapa_link']) ?>" target="_blank" rel="noopener"><?= e($CONTACTO['direccion']) ?></a></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
