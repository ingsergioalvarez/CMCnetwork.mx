<?php
defined('CMC') || exit;
require_once __DIR__ . '/datos.php';
?>
</main>

<footer class="pie">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-3">
        <img src="/imagenes/marca/logo-cmc-nav.png" alt="CMC Service Network" class="pie-logo" width="190" height="54" loading="lazy">
        <p class="texto-suave mt-3">Un solo socio tecnológico: IoT, automatización, cadena de suministro, conectividad y TI.</p>
        <p class="mb-1"><?php foreach ($CONTACTO['telefonos'] as $t): ?><a href="tel:<?= e($t['tel']) ?>"><?= e($t['texto']) ?></a><br><?php endforeach; ?></p>
      </div>
      <div class="col-sm-6 col-lg-3">
        <h4>Soluciones</h4>
        <ul>
          <?php foreach ($PILARES as $p): ?>
            <li><a href="<?= e($p['url']) ?>"><?= e($p['nombre']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php foreach ($LEGALES as $grupo => $docs): ?>
        <div class="col-sm-6 col-lg-3">
          <h4><?= e($grupo) ?></h4>
          <ul>
            <?php foreach ($docs as $d): ?>
              <li><a href="<?= e('/' . legal_url($d[1])) ?>" target="_blank" rel="noopener"><?= e($d[0]) ?><?= substr($d[1], -4) === '.m4a' ? ' ' . icono('volume-up') : '' ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="pie-final">
      <span>© <?= date('Y') ?> CMC Service Network, S.A. de C.V. · <a href="/residencial.php">Internet residencial</a></span>
      <span>Developed by SKS Developers</span>
    </div>
  </div>
</footer>

<?= sprite_iconos() ?>
<script><?= js_en_linea('app.js') ?></script>
</body>
</html>
