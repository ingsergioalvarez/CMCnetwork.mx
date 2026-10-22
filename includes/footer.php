<?php
defined('CMC') || exit;
require_once __DIR__ . '/datos.php';
?>
<footer class="pie">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-3">
        <img src="/imagenes/marca/logo-cmc-blanco-web.png" alt="CMC Service Network" class="pie-logo" loading="lazy">
        <p class="texto-suave mt-3">Conectividad empresarial y residencial con monitoreo 7 x 24.</p>
        <p class="mb-1"><?php foreach ($CONTACTO['telefonos'] as $t): ?><a href="tel:<?= e($t['tel']) ?>"><?= e($t['texto']) ?></a><br><?php endforeach; ?></p>
      </div>
      <div class="col-sm-6 col-lg-3">
        <h4>Servicios</h4>
        <ul>
          <li><a href="/corporativo.php#eventos">Internet para eventos</a></li>
          <li><a href="/corporativo.php">Internet corporativo</a></li>
          <li><a href="/residencial.php">Internet residencial</a></li>
        </ul>
      </div>
      <?php foreach ($LEGALES as $grupo => $docs): ?>
        <div class="col-sm-6 col-lg-3">
          <h4><?= e($grupo) ?></h4>
          <ul>
            <?php foreach ($docs as $d): ?>
              <li><a href="<?= e('/' . legal_url($d[1])) ?>" target="_blank" rel="noopener"><?= e($d[0]) ?><?= substr($d[1], -4) === '.m4a' ? ' <i class="bi bi-volume-up"></i>' : '' ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="pie-final">
      <span>© <?= date('Y') ?> CMC Service Network, S.A. de C.V.</span>
      <span>Developed by SKS Developers</span>
    </div>
  </div>
</footer>

<script src="/js/app.js?v=2" defer></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
</body>
</html>
